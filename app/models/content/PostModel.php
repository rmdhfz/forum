<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PostModel extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function list()
    {
        $data = $this->db->query("
            SELECT
            p.id,
            c.name as category,
            p.title,
            p.content,
            p.is_publish as status

            FROM 
                post p
            INNER JOIN category c ON p.category_id = c.id
            WHERE
                p.is_deleted IS NULL AND
                c.is_deleted IS NULL
        ");
        
        if ($data->num_rows() == 0) {
            json(null);   
        }
        
        $no = 0;
        $result = ['data'=>[]];
        foreach ($data->result() as $key => $value) { $no++;
            $option  = options($value->id);
            $status  = $value->status == 0 ? "<span class='badge bg-danger'>Draft</span>" : "<span class='badge bg-primary'>Publish</span>";
            $result['data'][$key] = [
                $no,
                $value->category,
                $value->title,
                $value->content,
                $status,
                $option
            ];
        }

        json($result);
    }
}