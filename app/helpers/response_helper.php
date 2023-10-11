<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('response')) {
    function response(bool $status = null, int $code = 200, string $message = "Message is null.", $data = null)
    {
        http_response_code($code);
        if ($data) {
            return ['status' => $status, 'message' => $message, 'data' => $data];
        }
        return ['status' => $status, 'message' => $message];
    }
}

if (!function_exists('json')) {
    function json($data = '')
    {
        $ci = &get_instance();
        $ci->output
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT))
            ->_display();
        exit();
    }
}

if (!function_exists('nopermission')) {
    function nopermission($msg = 'no permission.', $data = null)
    {
        json(response(false, 401, $msg, $data));
        return;
    }
}
if (!function_exists('error')) {
    function error($msg, $data = null)
    {
        json(response(false, 500, $msg, $data));
        return;
    }
}
if (!function_exists('notfound')) {
    function notfound($msg, $data = null)
    {
        json(response(false, 404, $msg, $data));
        return;
    }
}
if (!function_exists('badrequest')) {
    function badrequest($msg, $data = null)
    {
        json(response(false, 400, $msg, $data));
        return;
    }
}
if (!function_exists('success')) {
    function success($msg, $data = null)
    {
        json(response(true, 200, $msg, $data));
        return;
    }
}