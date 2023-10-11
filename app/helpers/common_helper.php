<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('checklogin')) {
    function checklogin()
    {
        if (!session('is_login')) {
            redirect(base_url());
        }
    }
}

if (!function_exists('template')) {
    function template($data)
    {
        $ci = &get_instance();
        return $ci->load->view('backend/index', $data);
    }
}

if (!function_exists('session')) {
    function session($session_name)
    {
        $ci = &get_instance();
        $ci->load->library('session');
        return $ci->session->userdata($session_name);
    }
}

if (!function_exists('get_os')) {
    function get_os()
    {
        $ci = &get_instance();
        $ci->load->library('user_agent');
        return $ci->agent->platform();
    }
}

if (!function_exists('get_ip_address')) {
    function get_ip_address()
    {
        $ci = &get_instance();
        return $ci->input->ip_address();
    }
}

if (!function_exists('get_user_agent')) {
    function get_user_agent()
    {
        $ci = &get_instance();
        $ci->load->library('user_agent');
        if ($ci->agent->is_browser()) {
            $agent = $ci->agent->browser() . $ci->agent->version();
        } elseif ($ci->agent->is_robot()) {
            $agent = $ci->agent->robot() . ': is robot';
        } elseif ($ci->agent->is_mobile()) {
            $agent = $ci->agent->mobile() . ': is mobile';
        } else {
            $agent = 'Tidak Teridentifikasi User Agent: unknown';
        }
        return $agent;
    }
}

if (!function_exists('check_is_upload')) {
    function check_is_upload($filename)
    {
        if (!empty($_FILES[$filename]['name'])) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('uploadfile')) {
    function uploadfile($path, $file)
    {
        if (!file_exists(DEFAULT_PATH_UPLOAD.$path)) {
            mkdir(DEFAULT_PATH_UPLOAD.$path, 755, true);
        } else {
            $config = [
                'overwrite'                 => 1,
                'upload_path'               => DEFAULT_PATH_UPLOAD.$path,
                'detect_mime'               => true,
                'encrypt_name'              => true,
                'mod_mime_fix'              => true,
                'allowed_types'             => "pdf|jpg|jpeg|png|pdf|xlsx|xls",
                'remove_spaces'             => true,
                'file_ext_tolower'          => true,
                'max_filename_increment'    => 100,
            ];
            $ci = &get_instance();
            $ci->load->library('upload', $config);
            if (!$ci->upload->do_upload($file)) {
                return false;
            }
            return $ci->upload->data();
        }
    }
}

if (!function_exists('verify_email')) {
    function verify_email($email)
    {
        if (!$email) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return $email;
    }
}

if (!function_exists('verify_url')) {
    function verify_url($url)
    {
        if (!$url) {
            return false;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        return $url;
    }
}

if (!function_exists('format_phone_number')) {
    function format_phone_number($n)
    {
        if (!$n) {
            return false;
        }
        $no = trim(strip_tags($n));
        $no = str_replace(" ", "", $n);
        $no = str_replace("(", "", $n);
        $no = str_replace(")", "", $n);
        $no = str_replace(".", "", $n);
        $no = str_replace("-", "", $n);

        if (!preg_match('/[^+0-9]/', trim($no))) {
            if (substr(trim($n), 0, 3) == '+62') {
                $no = '62' . substr($n, 3);
            } elseif (substr($n, 0, 1) == '0') {
                $no = '62' . substr($n, 1);
            }
        }
        return $no;
    }
}

if (!function_exists('check_password')) {
    function check_password($password)
    {
        if (strlen($password) < 8) {
            return "-8";
        }
        if (!preg_match("/[a-z]/", $password)) {
            return "!lowercase";
        }
        if (!preg_match("/[A-Z]/", $password)) {
            return "!uppercase";
        }
        if (!preg_match("/[!@#$%^&*()\-_=+{};:,<.>?]/", $password)) {
            return "!symbol";
        }
        return true;
    }
}

if (!function_exists('encrypt_data_display')) {
    function encrypt_data_display($data)
    {
        if (!$data) {
            return false;
        }
        $format     = "******";
        $prefix     = substr($data, 0, 3);
        $suffix     = substr($data, -3);
        return $prefix.$format.$suffix;
    }
}

if (!function_exists('post')) {
    function post($data)
    {
        if (!$data || is_array($data)) {
            return false;
        }
        
        $ci    = &get_instance();
        $input = $ci->input->post($data, true);
        return $ci->security->xss_clean(stripcslashes(htmlspecialchars(htmlentities($input))));
    }
}