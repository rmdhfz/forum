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
}