<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Post extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            redirect(base_url());
        }
    }

    # post
    function index_get()
    {
        template([
            'file'  =>  'modul/content/post/index'
        ]);
    }

    function list_get()
    {
        $this->load->model('content/PostModel', 'model');
        $this->model->list();
    }

    function data_get()
    {
        $read = read('id,category_id as category, title, content, is_publish as status', 'post', [
            'is_deleted'    =>  null,
        ]);

        if (!$read) {
            error('failed read data post');
        }
        success('success read data post', $read->result());
    }

    function create_post()
    {
        $category   = $this->post('category');
        $title      = $this->post('title');
        $content    = $this->post('content');
        $status     = $this->post('status');
        $data = [
            'category_id'   =>  $category,
            'title'         =>  $title,
            'content'       =>  $content,
            'is_publish'    =>  $status
        ];

        $create = create('post', $data, $data);
        if (!$create) {
            error("failed create data post");
        }
        success('success create data post');
    }
    function read_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $read = read('id,category_id as category, title, content, is_publish as status', 'post', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$read) {
            error('failed read data post');
        }
        success('success read data post', $read->row());
    }
    function update_post()
    {
        $id         = $this->post('id');
        $category   = $this->post('category');
        $title      = $this->post('title');
        $content    = $this->post('content');
        $status     = $this->post('status');
        $data = [
            'category_id'   =>  $category,
            'title'         =>  $title,
            'content'       =>  $content,
            'is_publish'    =>  $status
        ];
        $update = update('post', $data, [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$update) {
            error("failed update data post");
        }
        success('success update data post');
    }
    function delete_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $delete = delete('post', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$delete) {
            error('failed delete data post');
        }
        success('success delete data post');
    }
}