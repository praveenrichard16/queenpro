<?php

use App\Models\Setting;

$razorpay = [];

try {
    $razorpay = Setting::getValue('integration_payments_razorpay', []);
} catch (\Throwable $exception) {
    $razorpay = [];
}

return [
    'razorpay' => [
        'enabled' => $razorpay['enabled'] ?? false,
        'mode' => $razorpay['mode'] ?? env('RAZORPAY_MODE', 'sandbox'),
        'key_id' => $razorpay['key_id'] ?? env('RAZORPAY_KEY_ID'),
        'key_secret' => $razorpay['key_secret'] ?? env('RAZORPAY_KEY_SECRET'),
        'webhook_secret' => $razorpay['webhook_secret'] ?? env('RAZORPAY_WEBHOOK_SECRET'),
    ],
];

