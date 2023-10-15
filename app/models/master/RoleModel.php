<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RoleModel extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function list()
    {
        $data = $this->db->query("
            SELECT
            id,
            name

            FROM role
            WHERE
            is_deleted IS NULL
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
                $value->name,
                $option
            ];
        }

        json($result);
    }
}