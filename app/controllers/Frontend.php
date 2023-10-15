<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Frontend extends RestController {

    function __construct(){
        parent::__construct();
        if (session('is_login')) {
            redirect(base_url('dashboard'));
        }
    }
    
    function index_get()
    {
        $this->load->view('frontend/index');
    }

    function verify_post()
    {
        $email      = $this->post('email');
        $password   = $this->post('password');
        if (!$email || !$password) {
            badrequest('Permintaan buruk');
        }

        # cek format email
        if (!verify_email($email)) {
            badrequest('Periksa kembali email Anda');
        }

        # load database
        $this->load->database();
        
        # cek data users berdasarkan email
        $check = $this->db->query("
            SELECT 
            u.id, 
            u.nis, 
            u.name, 
            u.email, 
            u.profile, 
            u.password,
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
                INNER JOIN access a ON a.users_id = u.id
            WHERE 
                u.email = ? AND
                u.is_deleted IS NULL AND
                a.is_deleted IS NULL", [$email]);
        if ($check->num_rows() == 0) {
            # email tidak ditemukan
            # tapi berikan pesan general saja.
            notfound('Email atau kata sandi yang Anda masukkan salah.');
        }

        # buat $db dari hasil pengecekan.
        $db = $check->row();

        # periksa kata sandi
        $verify = password_verify($password, $db->password);
        if (!$verify) {
            # kata sandi tidak sesuai
            # berikan pesan general.
            notfound('Email atau kata sandi yang Anda masukkan salah.');
        }

        # jika sampai pada proses ini
        # maka email dan kata sandi benar

        # siapkan data untuk membuat sesi
        $data = [
            'is_login'                      =>  1,
            'last_login'                    =>  date('d-m-Y h:i:s'),
            'user_id'                       =>  $db->id,
            'user_name'                     =>  $db->name,
            'user_email'                    =>  $db->email,
            'user_nis'                      =>  $db->nis,
            'user_profile'                  =>  $db->profile,
            'user_access_can_create'        =>  $db->can_create,
            'user_access_can_read'          =>  $db->can_read,
            'user_access_can_update'        =>  $db->can_update,
            'user_access_can_delete'        =>  $db->can_delete,
            'user_access_can_approve'       =>  $db->can_approve,
            'user_access_can_reject'        =>  $db->can_reject,
            'user_access_can_upload'        =>  $db->can_upload,
            'user_access_can_download'      =>  $db->can_download,
            'user_access_can_open_ticket'   =>  $db->can_open_ticket,
            'user_access_can_hold_ticket'   =>  $db->can_hold_ticket,
            'user_access_can_close_ticket'  =>  $db->can_close_ticket
        ];

        # load library session
        $this->load->library('session');

        # buat sesi
        $this->session->set_userdata($data);

        # kirim respon berhasil
        success('Selamat, login berhasil', [
            'redirect'  =>  base_url('dashboard')
        ]);
    }
}