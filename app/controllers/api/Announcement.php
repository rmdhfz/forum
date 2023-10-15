<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Announcement extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            http_response_code(401);
            return;
        }
    }

    # announcement
    function index_get()
    {
        $this->load->database();
        $data = $this->db->query("
            SELECT
            p.id,
            cp.name as category,
            p.title,
            p.views,
            SUBSTRING(p.content, 1, 50) as content,
            p.created_at,
            p.created_by

            FROM
                post p
            INNER JOIN category cp ON p.category_id = cp.id
            LEFT JOIN comment c ON c.post_id = p.id

            WHERE
                p.is_deleted IS NULL AND
                cp.is_deleted IS NULL AND
                c.is_deleted IS NULL AND
                p.is_publish = ? AND
                cp.name = ? LIMIT 3
        ", [1, "pengumuman"]);
        success('success', $data->result());
    }
}