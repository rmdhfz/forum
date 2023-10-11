<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('request_email_service')) {
    function request_email_service($endpoint, $data, $method, $token = null)
    {
        if (!$data) {
            return false;
        }
        $ch = curl_init();
        $options = [
            CURLOPT_URL             => "https://api.dukodu.id/".$endpoint,
            CURLOPT_CUSTOMREQUEST   => $method,
            CURLOPT_POSTFIELDS      => http_build_query($data),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      =>  [
                'Origin: https://app.dukodu.id',
                'Referer: https://app.dukodu.id',
                'Authorization: Bearer ' . $token
            ]
        ];
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('get_token_email_service')) {
    function get_token_email_service()
    {
        $login = request_email_service('auth', [
            'username'  =>  USERNAME_API_DUKODU,
            'password'  =>  PASSWORD_API_DUKODU
        ], 'PUT');

        $response = json_decode($login);
        return $response->data->token;
    }
}

if (!function_exists('send_email_service')) {
    function send_email_service($recipient, $subject, $message)
    {
        if (!$recipient || !$subject || !$message) {
            return false;
        }
        $send  = request_email_service('email.service/2023/04/v1/send', [
            'source'        =>  'dukodu',
            'sender'        =>  'Dukodu Internet Super Cepat <no-reply@dukodu.id>',
            'recipient'     =>  $recipient,
            'subject'       =>  $subject,
            'message'       =>  $message,
            'bcc'           =>  'hafiz@transhybrid.net.id',
        ], 'PUT', get_token_email_service());
        return $send;
    }
}