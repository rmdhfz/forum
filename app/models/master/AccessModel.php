<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AccessModel extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function list()
    {
        $data = $this->db->query("
            SELECT
            a.id,
            u.name as users,
            a.can_create,
            a.can_read,
            a.can_update,
            a.can_delete,
            a.can_approve,
            a.can_reject,
            a.can_upload,
            a.can_download,
            a.can_open_ticket,
            a.can_hold_ticket,
            a.can_close_ticket

            FROM 
                access a
            INNER JOIN users u ON a.users_id = u.id

            WHERE
                a.is_deleted IS NULL AND
                u.is_deleted IS NULL
        ");
        
        if ($data->num_rows() == 0) {
            json(null);   
        }
        
        $no = 0;
        $result = ['data'=>[]];
        foreach ($data->result() as $key => $value) { $no++;
            $option = options($value->id);
            $result['data'][$key] = [
                $no,
                $value->users,
                $value->can_create == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_read == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_update == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_delete == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_approve == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_reject == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_upload == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_download == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_open_ticket == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_hold_ticket == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $value->can_close_ticket == 1 ? "<span class='badge bg-success'>true</span>" : "<span class='badge bg-danger'>false</span>",
                $option
            ];
        }

        json($result);
    }
}