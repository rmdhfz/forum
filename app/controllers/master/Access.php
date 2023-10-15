<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Access extends RestController {

    function __construct(){
        parent::__construct();

        # periksa apakah ada session is_login 
        # jika tidak ada redirect ke base_url
        if (!session('is_login')) {
            redirect(base_url());
        }
    }
    
    # access
    function index_get()
    {
        template([
            'file'  =>  'modul/master/access/index'
        ]);
    }

    function list_get()
    {
        $this->load->model('master/AccessModel', 'model');
        $this->model->list();
    }

    function create_post()
    {
        $users_id              = $this->post('users');
        $can_create            = $this->post('create') === 'on' ? 1 : 0;
        $can_read              = $this->post('read') === 'on' ? 1 : 0;
        $can_update            = $this->post('update') === 'on' ? 1 : 0;
        $can_delete            = $this->post('deleted') === 'on' ? 1 : 0;
        $can_approve           = $this->post('approve') === 'on' ? 1 : 0;
        $can_reject            = $this->post('reject') === 'on' ? 1 : 0;
        $can_openticket        = $this->post('openticket') === 'on' ? 1 : 0;
        $can_holdticket        = $this->post('holdticket') === 'on' ? 1 : 0;
        $can_closeticket       = $this->post('closeticket') === 'on' ? 1 : 0;
        $can_upload            = $this->post('upload') === 'on' ? 1 : 0;
        $can_download          = $this->post('download') === 'on' ? 1 : 0;

        $data = [
            'users_id'          =>  $users_id,
            'can_create'        =>  $can_create,
            'can_read'          =>  $can_read,
            'can_update'        =>  $can_update,
            'can_delete'        =>  $can_delete,
            'can_approve'       =>  $can_approve,
            'can_reject'        =>  $can_reject,
            'can_open_ticket'   =>  $can_openticket,
            'can_hold_ticket'   =>  $can_holdticket,
            'can_close_ticket'  =>  $can_closeticket,
            'can_upload'        =>  $can_upload,
            'can_download'      =>  $can_download
        ];

        $create = create('access', $data, $data);
        if (!$create) {
            error("failed create data access");
        }
        success('success create data access');
    }
    function read_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $read = read('id,users_id,can_create,can_read,can_update,can_delete,can_approve,can_reject,can_upload,can_download,can_open_ticket,can_hold_ticket,can_close_ticket', 'access', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$read) {
            error('failed read data access');
        }
        success('success read data access', $read->row());
    }
    function update_post()
    {
        $id                    = $this->post('id');
        $users_id              = $this->post('users');
        $can_create            = $this->post('create') === 'on' ? 1 : 0;
        $can_read              = $this->post('read') === 'on' ? 1 : 0;
        $can_update            = $this->post('update') === 'on' ? 1 : 0;
        $can_delete            = $this->post('deleted') === 'on' ? 1 : 0;
        $can_approve           = $this->post('approve') === 'on' ? 1 : 0;
        $can_reject            = $this->post('reject') === 'on' ? 1 : 0;
        $can_openticket        = $this->post('openticket') === 'on' ? 1 : 0;
        $can_holdticket        = $this->post('holdticket') === 'on' ? 1 : 0;
        $can_closeticket       = $this->post('closeticket') === 'on' ? 1 : 0;
        $can_upload            = $this->post('upload') === 'on' ? 1 : 0;
        $can_download          = $this->post('download') === 'on' ? 1 : 0;

        $data = [
            'users_id'          =>  $users_id,
            'can_create'        =>  $can_create,
            'can_read'          =>  $can_read,
            'can_update'        =>  $can_update,
            'can_delete'        =>  $can_delete,
            'can_approve'       =>  $can_approve,
            'can_reject'        =>  $can_reject,
            'can_open_ticket'   =>  $can_openticket,
            'can_hold_ticket'   =>  $can_holdticket,
            'can_close_ticket'  =>  $can_closeticket,
            'can_upload'        =>  $can_upload,
            'can_download'      =>  $can_download
        ];

        $update = update('access', $data, [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$update) {
            error("failed update data access");
        }
        success('success update data access');
    }
    function delete_post()
    {
        $id = $this->post('id');
        if (!$id) {
            badrequest('parameter is required');
        }

        $delete = delete('access', [
            'is_deleted'    =>  null,
            'id'            =>  $id
        ]);

        if (!$delete) {
            error('failed delete data access');
        }
        success('success delete data access');
    }
}