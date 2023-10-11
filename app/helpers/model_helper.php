<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('options')) {
    function options($id){
        if (!$id) {
            return false;
        }
        return '<button id="edit" data-id="'.$id.'" class="btn btn-flat btn-sm btn-info"><i class="ti ti-pencil" aria-hidden="true"></i> </button>
            <button id="delete" data-id="'.$id.'" class="btn btn-flat btn-sm btn-danger"><i class="ti ti-trash" aria-hidden="true"></i> </button>';
    }
}