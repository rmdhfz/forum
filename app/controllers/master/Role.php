<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Role extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            redirect(base_url());
        }
    }

    # role
    function index_get()
    {
        template([
            'file'  =>  'modul/master/role/index'
        ]);
    }

    function list_get()
    {
        $this->load->model('master/RoleModel', 'model');
        $this->model->list();
    }

    function data_get()
    {
        $read = read('id,name', 'role', [
            'is_deleted'    =>  null,
        ]);

        if (!$read) {
            error('failed read data role');
        }
        success('success read data role', $read->result());
    }

    function create_post()
    {
        $name       = $this->post('name');
        $data = [
            'name'          =>  $name,
        ];

        $create = create('role', $data, $data);
        if (!$create) {
            error("failed create data role");
        }
        success('success create data role');
    }
    function read_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $read = read('id, name', 'role', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$read) {
            error('failed read data role');
        }
        success('success read data role', $read->row());
    }
    function update_post()
    {
        $id         = $this->post('id');
        $name       = $this->post('name');

        $data = [
            'name'          =>  $name,
        ];
        $update = update('role', $data, [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$update) {
            error("failed update data role");
        }
        success('success update data role');
    }
    function delete_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $delete = delete('role', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$delete) {
            error('failed delete data role');
        }
        success('success delete data role');
    }
}