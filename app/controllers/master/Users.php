<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Users extends RestController {

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
            'file'  =>  'modul/master/users/index'
        ]);
    }

    function list_get()
    {
        $this->load->model('master/UsersModel', 'model');
        $this->model->list();
    }

    function data_get()
    {
        $read = read('id,name', 'users', [
            'is_deleted'    =>  null
        ]);

        if (!$read) {
            error('failed read data users');
        }
        success('success read data users', $read->result());
    }

    function create_post()
    {
        $email      = $this->post('email');
        if (!verify_email($email)) {
            badrequest("Format email tidak valid");
        }
        $nis        = $this->post('nis');
        $name       = $this->post('name');
        $password   = $this->post('password');
        $status     = $this->post('status');

        if (!check_is_upload('profile')) {
            badrequest("File profile dibutuhkan");
        }

        # upload file
        $path     = 'profile';
        $upload   = upload($path, 'profile');
        if (!$upload) {
            error('failed upload file');
        }

        $data = [
            'nis'           =>  $nis,
            'name'          =>  $name,
            'email'         =>  $email,
            'profile'       =>  base_url(DEFAULT_PATH_UPLOAD.$path.'/'.$upload['file_name']),
            'password'      =>  password_hash($password, PASSWORD_DEFAULT),
            'is_blocked'    =>  $status,
        ];

        $check = $data;
        unset($check['is_blocked']);

        $create = create('users', $data, $check);
        if (!$create) {
            error("failed create data users");
        }
        success('success create data users');
    }
    function read_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('Dibutuhkan parameter id');
        }

        $read = read('id,nis,name,email,is_blocked as status', 'users', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$read) {
            error('failed read data users');
        }
        success('success read data users', $read->row());
    }
    function update_post()
    {
        $id         = $this->post('id');
        $email      = $this->post('email');
        if (!verify_email($email)) {
            badrequest("Format email tidak valid");
        }
        $nis        = $this->post('nis');
        $name       = $this->post('name');
        $password   = $this->post('password');
        $status     = $this->post('status');

        if (!check_is_upload('profile')) {
            badrequest("File profile dibutuhkan");
        }

        # upload file
        $path     = 'profile';
        $upload   = upload($path, 'profile');
        if (!$upload) {
            error('failed upload file');
        }

        $data = [
            'nis'           =>  $nis,
            'name'          =>  $name,
            'email'         =>  $email,
            'profile'       =>  base_url(DEFAULT_PATH_UPLOAD.$path.'/'.$upload['file_name']),
            'password'      =>  password_hash($password, PASSWORD_DEFAULT),
            'is_blocked'    =>  $status,
        ];

        $update = update('users', $data, [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$update) {
            error("failed update data users");
        }
        success('success update data users');
    }
    function delete_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('Dibutuhkan parameter id');
        }

        $delete = delete('users', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$delete) {
            error('failed delete data users');
        }
        success('success delete data users');
    }
}