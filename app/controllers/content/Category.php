<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Category extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            redirect(base_url());
        }
    }

    # category
    function index_get()
    {
        template([
            'file'  =>  'modul/content/category/index'
        ]);
    }

    function list_get()
    {
        $this->load->model('content/CategoryModel', 'model');
        $this->model->list();
    }

    function data_get()
    {
        $read = read('id,name', 'category', [
            'is_deleted'    =>  null,
        ]);

        if (!$read) {
            error('failed read data category');
        }
        success('success read data category', $read->result());
    }

    function create_post()
    {
        $name       = $this->post('name');
        $data = [
            'name'          =>  $name,
        ];

        $create = create('category', $data, $data);
        if (!$create) {
            error("failed create data category");
        }
        success('success create data category');
    }
    function read_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $read = read('id, name', 'category', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$read) {
            error('failed read data category');
        }
        success('success read data category', $read->row());
    }
    function update_post()
    {
        $id         = $this->post('id');
        $name       = $this->post('name');

        $data = [
            'name'          =>  $name,
        ];
        $update = update('category', $data, [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$update) {
            error("failed update data category");
        }
        success('success update data category');
    }
    function delete_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $delete = delete('category', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$delete) {
            error('failed delete data category');
        }
        success('success delete data category');
    }
}