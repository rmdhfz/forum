<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UsersModel extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function list()
    {
        $data = $this->db->query("
            SELECT
            u.id,
            u.nis,
            r.name as role,
            u.name,
            u.email,
            u.profile,
            u.is_blocked as status

            FROM 
                users u
            INNER JOIN role r ON u.role_id = r.id
            WHERE
                u.is_deleted IS NULL AND
                r.is_deleted IS NULL
        ");
        
        if ($data->num_rows() == 0) {
            json(null);   
        }
        
        $no = 0;
        $result = ['data'=>[]];
        foreach ($data->result() as $key => $value) { $no++;
            $option  = options($value->id);
            $profile = $value->profile == null ? "-" : "<img draggable='false' src='$value->profile' class='img-thumbnail' style='width: 100px; height: 100px;' alt='profile'>";
            $status  = $value->status == 0 ? "<span class='badge bg-danger'>Diblokir</span>" : "<span class='badge bg-primary'>Aktif</span>";
            $result['data'][$key] = [
                $no,
                $value->nis,
                $value->role,
                $value->name,
                $value->email,
                $profile,
                $status,
                $option
            ];
        }

        json($result);
    }
}