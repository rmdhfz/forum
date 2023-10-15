<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Backend extends RestController {

    function __construct(){
        parent::__construct();
        if (!session('is_login')) {
            redirect(base_url());
        }
    }

    function index_get()
    {
        echo 'welcome to dashboard';
    }
}