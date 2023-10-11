<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Frontend extends RestController {

    

    function __construct(){
        parent::__construct();
        if (session('is_login')) {
            redirect(base_url('dashboard'));
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $this->middleware();
        }
    }

    private function middleware()
    {
        $rt = $this->post('rt');

        # check expiry links from rt
        $current_time = time();
        $duration     = $current_time - (int) $rt;

        if ($duration > REQUEST_TIME_ALLOWED && !$this->input->is_ajax_request()) {
            return http_response_code(401);
        }

        return true;
    }

    private function generate_otp()
    {
        $characters = '0123456789';
        $otp = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < 6; $i++) {
            $otp .= $characters[rand(0, $charactersLength - 1)];
        }
        return $otp;
    }

    private function update_last_login($user_id)
    {
        if (!$user_id) {
            return false;
        }
        $this->load->database();
        $update = $this->db->where('id', $user_id)->update('users', [
            'last_login'    =>  date('Y-m-d h:i:s')
        ]);
        if (!$update) {
            return false;
        }
        return true;
    }

    function index_get()
    {
        $this->load->view('frontend/index');
    }

    function ping_head()
    {
        echo 'OK';
    }

    function verify_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');

        if (!$username && !$password) {
            badrequest('parameter username and password is required');
        }

        $this->load->database();
        $check = $this->db->query("
            SELECT 
            u.id as user_id,
            e.id as employee_id,
            e.nip as employee_nip,
            e.name as employee_name,
            e.gender as employee_gender,
            e.email as employee_email,
            e.phone as employee_phone,
            e.img as employee_profile,
            e.esign as employee_esign,
            c.id as employee_company_id,
            u.username as user_username, 
            u.password, 
            u.salt, 
            u.is_2fa as user_is_2fa,
            u.is_block,
            a.can_create,
            a.can_read,
            a.can_update,
            a.can_delete,
            a.can_approve,
            a.can_reject,
            a.can_upload,
            a.can_download,
            a.can_open_ticket,
            a.can_hold_ticket,
            a.can_close_ticket

            FROM 
            users u
            INNER JOIN employee e ON u.employee_id = e.id
            INNER JOIN access a ON a.employee_id = e.id
            INNER JOIN position p ON e.position_id = p.id
            INNER JOIN company c ON p.company_id = c.id

            WHERE 
            u.username = ? AND 
            u.is_deleted IS NULL AND
            a.is_deleted IS NULL AND
            e.is_deleted IS NULL AND
            p.is_deleted IS NULL AND
            c.is_deleted IS NULL", [$username]);

        if ($check->num_rows() == 0) {
            notfound('Invalid username or password. Please try again.');
        }

        $db = $check->row();
        $verify = password_verify($db->salt.$password, $db->password);
        if (!$verify) {
            notfound('Invalid username or password. Please try again.');
        }

        # if is block
        if ($db->is_block) {
            error('Your account has been blocked!');
            return;
        }

        # login success - create session
        $sessions = [
            'is_login'      =>  1,
            'login_at'      =>  date('d-m-Y h:i:s'),
            
            'user_id'       =>  $db->user_id,
            'user_name'     =>  $db->user_username,
            'user_is_2fa'   =>  $db->user_is_2fa,

            'employee_id'           =>  $db->employee_id,
            'employee_nip'          =>  $db->employee_nip,
            'employee_name'         =>  $db->employee_name,
            'employee_gender'       =>  $db->employee_gender,
            'employee_email'        =>  $db->employee_email,
            'employee_phone'        =>  $db->employee_phone,
            'employee_profile'      =>  $db->employee_profile,
            'employee_esign'        =>  $db->employee_esign,
            'employee_company_id'   =>  $db->employee_company_id,

            'user_access_can_create'    =>  $db->can_create,
            'user_access_can_read'      =>  $db->can_read,
            'user_access_can_update'    =>  $db->can_update,
            'user_access_can_delete'    =>  $db->can_delete,
            'user_access_can_approve'   =>  $db->can_approve,
            'user_access_can_reject'    =>  $db->can_reject,
            'user_access_can_upload'    =>  $db->can_upload,
            'user_access_can_download'  =>  $db->can_download,
            'user_access_can_open_ticket'  =>  $db->can_open_ticket,
            'user_access_can_hold_ticket'  =>  $db->can_hold_ticket,
            'user_access_can_close_ticket' =>  $db->can_close_ticket
        ];

        # detect is 2fa ?
        if ($db->user_is_2fa) {
            $redirect = "2fa";

            # create otp and send via email
            # check otp expiration time
            $existing_otp = $this->db->query("SELECT * FROM otp WHERE user_id = ? AND expiration_time > ?", [$db->user_id, time()])->row();

            if (!$existing_otp) {
                
                # not found - create new
                $otp            = $this->generate_otp();
                $exptime        = time() + 60; # 1 minutes
                $create_otp     = $this->db->insert('otp', [
                    'user_id'           => $db->user_id,
                    'otp'               => $otp,
                    'expiration_time'   => $exptime
                ]);

                # handle error create otp
                if (!$create_otp) {
                    error('Failed to create OTP');
                }
            } else {
                $otp = $existing_otp->otp;
            }

            $this->load->helper('email');
            $message = "
                <b> Dear $db->employee_name, </b> <br>
                We have received a request to verify your account. <br>
                To proceed, please use the following One-Time Password (OTP): <br>
                OTP: $otp <br> <br>

                Please enter this OTP on the verification page to complete the process. <br>
                Please note that this OTP is valid for <b> 1 minute </b> from the time of this email. <br> <br>

                If you did not make this request, please disregard this email. Your account security is important to us. <br> <br>

                Sincerely, <br>
                <b> Trans Hybrid Communication. </b>
            ";
            $email = send_email_service($db->employee_email, '[Corp Trans] -  Your One-Time Password (OTP) for Account Verification', $message);
        }else{
            $redirect = base_url('dashboard');
            $this->load->library('session');
            $this->session->set_userdata($sessions);

            # update last login
            if (!$this->update_last_login($db->user_id)) {
                badrequest('Error: failed update last login');
            }
        }

        success('ok', [
            'redirect'  =>  $redirect,
            'result'    =>  $email
        ]);
    }

    function otp_verify_post()
    {
        $otp    = $this->post('otp');
        if (!$otp) {
            badrequest('parameter is required');
        }

        $this->load->database();
        $check  = $this->db->query("
            SELECT 
            u.id as user_id,
            e.id as employee_id,
            e.nip as employee_nip,
            e.name as employee_name,
            e.gender as employee_gender,
            e.email as employee_email,
            e.phone as employee_phone,
            e.img as employee_profile,
            e.esign as employee_esign,
            c.id as employee_company_id,
            u.username as user_username, 
            u.password, 
            u.salt, 
            u.is_2fa as user_is_2fa,
            u.is_block,
            a.can_create,
            a.can_read,
            a.can_update,
            a.can_delete,
            a.can_approve,
            a.can_reject,
            a.can_upload,
            a.can_download,
            a.can_open_ticket,
            a.can_hold_ticket,
            a.can_close_ticket

            FROM otp o
            INNER JOIN users u ON o.user_id = u.id
            INNER JOIN employee e ON u.employee_id = e.id
            INNER JOIN access a ON a.employee_id = e.id
            INNER JOIN position p ON e.position_id = p.id
            INNER JOIN company c ON p.company_id = c.id

            WHERE 
            o.otp = ? AND 
            o.expiration_time > ? AND
            u.is_deleted IS NULL AND
            a.is_deleted IS NULL AND
            e.is_deleted IS NULL AND
            p.is_deleted IS NULL AND
            c.is_deleted IS NULL", [$otp, time()]);

        if ($check->num_rows() == 0) {
            error('Oops! OTP entered is incorrect.');
        }

        $db = $check->row();

        # if is block
        if ($db->is_block) {
            error('your account has been blocked!');
            return;
        }

        # login success - create session
        $sessions = [
            'is_login'      =>  1,
            'login_at'      =>  date('d-m-Y h:i:s'),
            
            'user_id'       =>  $db->user_id,
            'user_name'     =>  $db->user_username,
            'user_is_2fa'   =>  $db->user_is_2fa,

            'employee_id'           =>  $db->employee_id,
            'employee_nip'          =>  $db->employee_nip,
            'employee_name'         =>  $db->employee_name,
            'employee_gender'       =>  $db->employee_gender,
            'employee_email'        =>  $db->employee_email,
            'employee_phone'        =>  $db->employee_phone,
            'employee_profile'      =>  $db->employee_profile,
            'employee_esign'        =>  $db->employee_esign,
            'employee_company_id'   =>  $db->employee_company_id,

            'user_access_can_create'    =>  $db->can_create,
            'user_access_can_read'      =>  $db->can_read,
            'user_access_can_update'    =>  $db->can_update,
            'user_access_can_delete'    =>  $db->can_delete,
            'user_access_can_approve'   =>  $db->can_approve,
            'user_access_can_reject'    =>  $db->can_reject,
            'user_access_can_upload'    =>  $db->can_upload,
            'user_access_can_download'  =>  $db->can_download,
            'user_access_can_open_ticket'  =>  $db->can_open_ticket,
            'user_access_can_hold_ticket'  =>  $db->can_hold_ticket,
            'user_access_can_close_ticket' =>  $db->can_close_ticket
        ];
        
        $this->load->library('session');
        $this->session->set_userdata($sessions);
        $this->db->where('otp', $otp)->update('otp', [
            'is_expired'    =>  1,
            'expired_at'    =>  date('Y-m-d h:i:s')
        ]);

        # update last login
        if (!$this->update_last_login($db->user_id)) {
            badrequest('Error: failed update last login');
        }

        success('OTP verified successfully.', [
            'redirect'  =>  base_url('dashboard')
        ]);
    }

}