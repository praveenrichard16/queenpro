<?php

use App\Models\Setting;

$smtp = [];

try {
    $smtp = Setting::getValue('integration_email_smtp', []);
} catch (\Throwable $exception) {
    $smtp = [];
}

$smtp = is_array($smtp) ? $smtp : [];

$host = $smtp['host'] ?? env('MAIL_HOST', 'smtp.mailgun.org');
$port = $smtp['port'] ?? env('MAIL_PORT', 587);
$encryption = $smtp['encryption'] ?? env('MAIL_ENCRYPTION', 'tls');
$username = $smtp['username'] ?? env('MAIL_USERNAME');
$password = $smtp['password'] ?? env('MAIL_PASSWORD');
$fromAddress = $smtp['from_email'] ?? env('MAIL_FROM_ADDRESS', 'hello@example.com');
$fromName = $smtp['from_name'] ?? env('MAIL_FROM_NAME', 'Queen Pro');

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => $host,
            'port' => $port,
            'encryption' => $encryption === 'none' ? null : $encryption,
            'username' => $username,
            'password' => $password,
            'timeout' => null,
            'auth_mode' => null,
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    */

    'from' => [
        'address' => $fromAddress,
        'name' => $fromName,
    ],
];

