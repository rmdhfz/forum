<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Backend extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            redirect(base_url());
        }
    }

    function index_get()
    {
        template([
            'file'  =>  'modul/dashboard/index'
        ]);
    }

    function logout_get()
    {
        # load library session
        $this->load->library('session');

        # hapus session menggunakan fungsi sess_destroy()
        $this->session->sess_destroy();

        # setelah berhasil redirect ke base_url
        redirect(base_url());
    }
}