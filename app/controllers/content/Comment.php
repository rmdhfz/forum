<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Comment extends RestController {

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
            'file'  =>  'modul/content/comment/index'
        ]);
    }

    function list_get()
    {
        $this->load->model('content/CommentModel', 'model');
        $this->model->list();
    }

    function data_get()
    {
        $read = read('id,post_id as post, user_id as user, comment', 'comment', [
            'is_deleted'    =>  null,
        ]);

        if (!$read) {
            error('failed read data comment');
        }
        success('success read data comment', $read->result());
    }

    function create_post()
    {
        $id      = $this->post('id');
        $user_id = $this->post('user');
        $post_id = $this->post('post');
        $comment = $this->post('comment');

        $data = [
            'post_id'   =>  $post_id,
            'user_id'   =>  $user_id,
            'comment'   =>  $comment
        ];

        $create = create('comment', $data, $data);
        if (!$create) {
            error("failed create data comment");
        }
        success('success create data comment');
    }
    function read_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $read = read('id,post_id as post, user_id as user, comment', 'comment', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$read) {
            error('failed read data comment');
        }
        success('success read data comment', $read->row());
    }
    function update_post()
    {
        $id      = $this->post('id');
        $user_id = $this->post('user');
        $post_id = $this->post('post');
        $comment = $this->post('comment');

        $data = [
            'post_id'   =>  $post_id,
            'user_id'   =>  $user_id,
            'comment'   =>  $comment
        ];
        $update = update('comment', $data, [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$update) {
            error("failed update data comment");
        }
        success('success update data comment');
    }
    function delete_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $delete = delete('comment', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$delete) {
            error('failed delete data comment');
        }
        success('success delete data comment');
    }
}