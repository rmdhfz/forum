<?php defined('BASEPATH') or exit('No direct script access allowed');

/*

create function for 
- crud
- checking access
- save activity

return boolean

*/

if ( ! function_exists('check_access')) {
    function check_access($activity)
    {
        if ($activity === "create") {
            if (!session('user_access_can_create')) {
                return false;
            }
            return true;
        }else if ($activity === "read") {
            if (!session('user_access_can_read')) {
                return false;
            }
            return true;
        }else if ($activity === "update") {
            if (!session('user_access_can_update')) {
                return false;
            }
            return true;
        }else if ($activity === "delete") {
            if (!session('user_access_can_delete')) {
                return false;
            }
            return true;
        }else if ($activity === "approve") {
            if (!session('user_access_can_approve')) {
                return false;
            }
            return true;
        }else if ($activity === "reject") {
            if (!session('user_access_can_reject')) {
                return false;
            }
            return true;
        }else if ($activity === "upload") {
            if (!session('user_access_can_upload')) {
                return false;
            }
            return true;
        }else if ($activity === "download") {
            if (!session('user_access_can_download')) {
                return false;
            }
            return true;
        }else if ($activity === "terminate") {
            if (!session('user_access_can_terminate')) {
                return false;
            }
            return true;
        }else if ($activity === "unterminate") {
            if (!session('user_access_can_unterminate')) {
                return false;
            }
            return true;
        }else if ($activity === "open_ticket") {
            if (!session('user_access_can_open_ticket')) {
                return false;
            }
            return true;
        }else if ($activity === "hold_ticket") {
            if (!session('user_access_can_hold_ticket')) {
                return false;
            }
            return true;
        }else if ($activity === "close_ticket") {
            if (!session('user_access_can_close_ticket')) {
                return false;
            }
            return true;
        }else{
            return false;
        }
    }
}

if ( ! function_exists('check_is_exist')) {
    function check_is_exist($table, $where)
    {
        if (!$table || !$where) {
            return false;
        }
        $ci = &get_instance();
        $where['is_deleted'] = null;
        return ($ci->db->limit(1)->get_where($table, $where)->num_rows() === 0);
    }
}

if ( ! function_exists('check_table_is_exist')) {
    function check_table_is_exist($table)
    {
        if (!$table) {
            return false;
        }

        # check table is exist or not
        $ci = &get_instance();
        $ci->load->database();
        return $ci->db->table_exists($table);
    }
}

if ( ! function_exists('write_logs')) {
    function write_logs($activity, $data)
    {
        if (!$activity || !$data) {
            return false;
        }
        $data = [
            'id_user'       =>  session('user_id'),
            'activity'      =>  $activity,
            'ip_address'    =>  get_ip_address(),
            'user_agent'    =>  get_user_agent(),
            'os'            =>  get_os(),
            'payload'       =>  json_encode($data),
        ];
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->trans_start();
        $ci->db->trans_strict();
        $ci->db->insert('history_activity', $data);

        if (!$ci->db->trans_status()) {
            $ci->db->trans_rollback();
            return false;
        }

        $ci->db->trans_commit();
        return true;
    }
}

if ( ! function_exists('create')) {
    function create($table, $data, $is_unique = false)
    {
        if (!$table || !$data) {
            return false;
        }
        
        # check access
        $check = check_access('create');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # check table first
        $check = check_table_is_exist($table);
        if (!$check) {
            error("table $table is not found");
        }

        if ($is_unique) {
            $check = check_is_exist($table, $is_unique);
            if (!$check) {
                badrequest("Data already exist. Please check again!");
                return;
            }
        }

        $data['created_by'] = session('user_name');
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->trans_start();
        $ci->db->trans_strict();
        $ci->db->insert($table, $data);
        $insert_id = $ci->db->insert_id();

        if (!$ci->db->trans_status()) {
            $ci->db->trans_rollback();
            return false;
        }

        # write logs
        $ci->db->trans_commit();
        $logs = write_logs('save data '.$table, $data);
        if (!$logs) {
            return false;
        }

        return $insert_id;
    }
}

if ( ! function_exists('read')) {
    function read($field, $table, $where)
    {
        if (!$field || !$table || !$where) {
            return false;
        }
        
        # check access
        $check = check_access('read');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # check table first
        $check = check_table_is_exist($table);
        if (!$check) {
            error("table $table is not found");
        }

        $ci = &get_instance();
        $ci->load->database();

        # check first
        $check = $ci->db->select('id')->from($table)->where('is_deleted', null)->get()->num_rows();
        if ($check == 0) {
            return false; # data not found
        }

        $get = $ci->db->select($field)
                        ->from($table)
                        ->where($where)
                        ->get();
        # write logs
        $logs = write_logs('read data '.$table, ['field' => $field, 'table' => $table, 'where' => $where]);
        if (!$logs) {
            return false;
        }
        return $get;
    }
}

if ( ! function_exists('update')) {
    function update($table, $data, $where)
    {
        if (!$table || !$data || !$where) {
            return false;
        }
        
        # check access
        $check = check_access('update');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # check table first
        $check = check_table_is_exist($table);
        if (!$check) {
            error("table $table is not found");
        }

        $data['updated_at'] = date('Y-m-d h:i:s');
        $data['updated_by'] = session('user_name');
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->trans_start();
        $ci->db->trans_strict();
        $ci->db->update($table, $data, $where);

        if (!$ci->db->trans_status()) {
            $ci->db->trans_rollback();
            return false;
        }

        # write logs
        $ci->db->trans_commit();
        $logs = write_logs('update data '.$table, ['data' => $data, 'where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    }
}

if ( ! function_exists('delete')) {
    function delete($table, $where)
    {
        if (!$table || !$where) {
            return false;
        }
        
        # check access
        $check = check_access('delete');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # check table first
        $check = check_table_is_exist($table);
        if (!$check) {
            error("table $table is not found");
        }

        $ci = &get_instance();
        $ci->load->database();
        $delete = $ci->db->update($table, [
            'is_deleted'    =>  1,
            'deleted_at'    =>  date('Y-m-d h:i:s'),
            'deleted_by'    =>  session('user_name'),
        ], $where);

        if (!$delete) {
            return false;
        }

        # write logs
        $logs = write_logs('delete data '.$table, ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    }
}

if ( ! function_exists('approve')) {
    function approve($table, $where)
    {
        if (!$table || !$where) {
            return false;
        }
        
        # check access
        $check = check_access('approve');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # check table first
        $check = check_table_is_exist($table);
        if (!$check) {
            error("table $table is not found");
        }

        $ci = &get_instance();
        $ci->load->database();
        $delete = $ci->db->update($table, [
            'is_approved'   =>  1,
            'approved_at'   =>  date('Y-m-d h:i:s'),
            'approved_by'   =>  session('user_name'),
        ], $where);

        if (!$delete) {
            return false;
        }

        # write logs
        $logs = write_logs('approve data '.$table, ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    }
}

if ( ! function_exists('reject')) {
    function reject($table, $where)
    {
        if (!$table || !$where) {
            return false;
        }
        
        # check access
        $check = check_access('reject');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # check table first
        $check = check_table_is_exist($table);
        if (!$check) {
            error("table $table is not found");
        }

        $ci = &get_instance();
        $ci->load->database();
        $delete = $ci->db->update($table, [
            'is_approved'       =>  0,
            'rejected_at'       =>  date('Y-m-d h:i:s'),
            'rejected_by'       =>  session('user_name'),
        ], $where);

        if (!$delete) {
            return false;
        }

        # write logs
        $logs = write_logs('reject data '.$table, ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    }
}

if ( ! function_exists('upload')) {
    function upload($file, $path)
    {
        if (!$file || !$path) {
            return false;
        }
        
        # check access
        $check = check_access('upload');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        # upload file
        $upload = uploadfile($path, $file);
        if (!$upload) {
            return false;
        }

        # write logs
        $logs = write_logs('upload file '.$file.' at '.date('d-m-Y h:i:s').' to '.$path, ['file' => $file, 'path' => $path]);
        if (!$logs) {
            return false;
        }

        return $upload;
    }
}


if ( ! function_exists('terminate')) {
    function terminate($where)
    {
        if (!$where) {
            return false;
        }

        # check access
        $check = check_access('terminate');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        $ci = &get_instance();
        $ci->load->database();
        $terminate = $ci->db->update('orders', [
            'is_terminate'      => true,
            'terminated_at'     => date('Y-m-d h:i:s'),
            'terminated_by'     => session('user_name'),
        ], $where);

        if (!$terminate) {
            return false;
        }

        # write logs
        $logs = write_logs('terminate order', ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    } 
}

if ( ! function_exists('unterminate')) {
    function unterminate($where)
    {
        if (!$where) {
            return false;
        }

        # check access
        $check = check_access('unterminate');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        $ci = &get_instance();
        $ci->load->database();
        $unterminate = $ci->db->update('orders', [
            'is_terminate'      => NULL,
            'terminated_at'     => NULL,
            'terminated_by'     => NULL,
        ], $where);

        if (!$unterminate) {
            return false;
        }

        # write logs
        $logs = write_logs('unterminate order', ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    } 
}

if ( ! function_exists('open_ticket')) {
    function open_ticket($where, $id)
    {
        if (!$where) {
            return false;
        }

        # check access
        $check = check_access('open_ticket');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        $ci = &get_instance();
        $ci->load->database();
        $open = $ci->db->update('ticket', [
            'status'    => 'open',
        ], $where);

        if (!$open) {
            return false;
        }

        # insert log ticket
        $history = $ci->db->insert('ticket_status_history', [
            'id_ticket'     =>  $id,
            'status'        =>  'open',
            'created_by'    =>  session('user_name'),
        ]);

        if (!$history) {
            return false;
        }

        # write logs
        $logs = write_logs('open ticket', ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    } 
}

if ( ! function_exists('hold_ticket')) {
    function hold_ticket($where, $id)
    {
        if (!$where) {
            return false;
        }

        # check access
        $check = check_access('hold_ticket');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        $ci = &get_instance();
        $ci->load->database();
        $hold = $ci->db->update('ticket', [
            'status'    => 'hold',
        ], $where);

        if (!$hold) {
            return false;
        }

        # insert log ticket
        $history = $ci->db->insert('ticket_status_history', [
            'id_ticket'     =>  $id,
            'status'        =>  'hold',
            'created_by'    =>  session('user_name'),
        ]);

        if (!$history) {
            return false;
        }

        # write logs
        $logs = write_logs('hold ticket', ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    } 
}

if ( ! function_exists('close_ticket')) {
    function close_ticket($where, $id)
    {
        if (!$where) {
            return false;
        }

        # check access
        $check = check_access('close_ticket');
        if (!$check) {
            nopermission('You do not have permission to perform this operation');
        }

        $ci = &get_instance();
        $ci->load->database();
        $close = $ci->db->update('ticket', [
            'status'    => 'close',
            'close_at'  =>  date('Y-m-d h:i:s'),
            'close_by'  =>  session('user_name'),
        ], $where);

        if (!$close) {
            return false;
        }

        # insert log ticket
        $history = $ci->db->insert('ticket_status_history', [
            'id_ticket'     =>  $id,
            'status'        =>  'close',
            'created_by'    =>  session('user_name'),
        ]);

        if (!$history) {
            return false;
        }

        # write logs
        $logs = write_logs('close ticket', ['where' => $where]);
        if (!$logs) {
            return false;
        }

        return true;
    } 
}