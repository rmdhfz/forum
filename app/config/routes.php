<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route = [
    '404_override'          =>  '',
    'default_controller'    =>  'Frontend',
    'verify'                =>  'Frontend/verify',

    # dashboard
    'dashboard'             =>  'Backend',
    'logout'                =>  'Backend/logout',
];
