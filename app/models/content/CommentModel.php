<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CommentModel extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function list()
    {
        $data = $this->db->query("
            SELECT 
            c.id,
            u.name as user,
            c.comment,
            c.created_at
            FROM 
                comment c
            INNER JOIN users u ON c.user_id = u.id
            INNER JOIN post p ON c.post_id = p.id
            WHERE 
                c.is_deleted IS NULL AND
                u.is_deleted IS NULL AND
                p.is_deleted IS NULL
        ");
        
        if ($data->num_rows() == 0) {
            json(null);   
        }
        
        $no = 0;
        $result = ['data'=>[]];
        foreach ($data->result() as $key => $value) { $no++;
            $option  = options($value->id);
            $result['data'][$key] = [
                $no,
                $value->user,
                $value->comment,
                sometime_ago(strtotime($value->created_at)),
                $option
            ];
        }

        json($result);
    }
}