<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Content extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            http_response_code(401);
            return;
        }
    }

    # content
    function index_get()
    {
        $this->load->database();
        $data = $this->db->query("
            SELECT
            p.id,
            cp.name as category,
            p.title,
            p.views,
            count(c.post_id) as totalcomment,
            p.content,
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
                p.is_publish IS NOT NULL
        ");
        success('success', $data->result());
    }
}