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
        $check = $this->db->query("SELECT id, views FROM post WHERE id = ? AND is_deleted IS NULL AND is_publish = ?", [$id, 1]);
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

        echo 'WELCOME';
    }
}