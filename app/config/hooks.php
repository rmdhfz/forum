<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller'][] = [
    'class'         =>  'Log',
    'function'      =>  'run',
    'filename'      =>  'Log.php',
    'filepath'      =>  'hooks',
];