<?php

use App\Models\Setting;

$google = [];
$facebook = [];
$whatsappMeta = [];

try {
    $google = Setting::getValue('integration_social_google', []);
    $facebook = Setting::getValue('integration_social_facebook', []);
    $whatsappMeta = Setting::getValue('integration_whatsapp_meta', []);
} catch (\Throwable $exception) {
    $google = [];
    $facebook = [];
    $whatsappMeta = [];
}

$google = is_array($google) ? $google : [];
$facebook = is_array($facebook) ? $facebook : [];
$whatsappMeta = is_array($whatsappMeta) ? $whatsappMeta : [];

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'google' => [
        'client_id' => $google['client_id'] ?? env('GOOGLE_CLIENT_ID'),
        'client_secret' => $google['client_secret'] ?? env('GOOGLE_CLIENT_SECRET'),
        'redirect' => $google['redirect_uri'] ?? env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => $facebook['app_id'] ?? env('FACEBOOK_CLIENT_ID'),
        'client_secret' => $facebook['app_secret'] ?? env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => $facebook['redirect_uri'] ?? env('FACEBOOK_REDIRECT_URI'),
    ],

    'whatsapp' => [
        'enabled' => $whatsappMeta['enabled'] ?? false,
        'api_token' => $whatsappMeta['access_token'] ?? env('WHATSAPP_API_TOKEN'),
        'phone_number_id' => $whatsappMeta['phone_number_id'] ?? env('WHATSAPP_PHONE_NUMBER_ID'),
        'whatsapp_business_account_id' => $whatsappMeta['whatsapp_business_account_id'] ?? env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'version' => $whatsappMeta['api_version'] ?? env('WHATSAPP_API_VERSION', 'v19.0'),
        'language' => $whatsappMeta['language'] ?? env('WHATSAPP_LANGUAGE', 'en'),
        'templates' => [
            'order_created' => $whatsappMeta['template_order_created'] ?? env('WHATSAPP_TEMPLATE_ORDER_CREATED', null),
            'order_status_updated' => $whatsappMeta['template_order_status_updated'] ?? env('WHATSAPP_TEMPLATE_ORDER_STATUS_UPDATED', null),
        ],
    ],
];

