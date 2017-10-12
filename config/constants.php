<?php

//$api_domain_url = env('APP_URL');
$api_domain_url = 'http://localhost:8102/';
$api_path_url = $api_domain_url;

//$remote_base_url = "http://mschools.co.ke/api2/";
$remote_base_url = "http://41.215.126.10/api2/";
$remote_api_url = $remote_base_url . "api/";

return [
    
    'site' => [
        'url' => $api_domain_url,
        'cache_minutes' => 5,
        'cache_minutes_low' => 60,
    ],

    'email' => [
        'from_address' => env('MAIL_FROM_ADDRESS'),
        'from_name' => env('MAIL_FROM_NAME'),
    ],

    'passport' => [
        'client_id' => env('PASSPORT_CLIENT_ID'),
        'client_secret' => env('PASSPORT_CLIENT_SECRET'),
        'token_url' => $remote_base_url . "oauth/token",
        'local_token_url' => $api_domain_url . "oauth/token",
    ],

    'bulk_sms' => [ 
        'send_sms_url' => $remote_api_url . "sms/sendsms",
        'get_sms_data_url' => $remote_api_url . "sms/getaccount",
        'src' => env('BULK_SMS_SRC'),
        'usr' => env('BULK_SMS_USR'),
        'pass' => env('BULK_SMS_PASS'),
    ],

    'oauth' => [
        'username' => env('OAUTH_USERNAME'),
        'password' => env('OAUTH_PASSWORD'),
        'client_id' => env('OAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_CLIENT_SECRET'),
    ],

    'sms_types' => [ 
        'registration_sms' => "1",
        'recommendation_sms' => "2",
        'resent_registration_sms' => "3",
        'forgot_password_sms' => "4",
        'confirm_number_sms' => "5",
        'company_sms' => "6",
    ],

    'status' => [ 
        'active' => "1",
        'disabled' => "2",
        'suspended' => "3",
        'expired' => "4",
        'pending' => "5",
        'confirmed' => "6",
        'cancelled' => "7",
        'sent' => "8",
        'inactive' => "99",
    ]

];