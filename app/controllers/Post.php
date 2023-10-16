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

    function index_get(int $id = 0, string $title = '')
    {
        if (!$id || $title === '') {
            redirect(base_url());
            return;
        }

        $this->load->database();
        $check = $this->db->query("SELECT * FROM post WHERE id = ? AND is_deleted IS NULL AND is_publish = ?", [$id, 1]);
        if ($check->num_rows() == 0) {
            redirect(base_url());
            return;
        }

        # update
        $update = $this->db->where('id', $id)->update('post', [
            'views' =>  $check->row()->views + 1
        ]);

        if (!$update) {
            echo 'Gagal mengupdate data views post';
            return;
        }

        # get data comment
        $comment = $this->db->query("
            SELECT 
            c.id,
            u.name as user,
            u.profile, 
            c.comment,
            c.created_at
            FROM 
                comment c
            INNER JOIN users u ON c.user_id = u.id
            INNER JOIN post p ON c.post_id = p.id
            WHERE 
                c.post_id = ? AND 
                c.is_deleted IS NULL AND
                u.is_deleted IS NULL AND
                p.is_deleted IS NULL
        ",[$id]);
        $response = [
            'post'      =>  $check->row(),
            'comment'   =>  $comment->result(),
        ];

        $this->load->view('frontend/detail', $response);
    }

    function comment_post()
    {
        $user_id = session('user_id');
        $post_id = $this->post('post_id');
        $comment = $this->post('comment');

        $data = [
            'post_id'   =>  $post_id,
            'user_id'   =>  $user_id,
            'comment'   =>  $comment
        ];

        $submit = $this->db->insert('comment', $data);
        if (!$submit) {
            error("Gagal meenambahkan komentar");
        }

        success("Berhasil meenambahkan komentar");
    }
}