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
        $check = $this->db->query("SELECT id, nis, name, email, profile, password FROM users WHERE email = ? AND is_deleted IS NULL", [$email]);
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
            'is_login'      =>  1,
            'last_login'    =>  date('d-m-Y h:i:s'),
            'user_name'     =>  $db->name,
            'user_email'    =>  $db->email,
            'user_nis'      =>  $db->nis,
            'user_profile'  =>  $db->profile
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