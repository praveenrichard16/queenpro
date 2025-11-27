<?php

return [
    'window_minutes' => 60,
    'channels' => ['email', 'sms', 'whatsapp'],
    'email' => [
        'from_name' => env('APP_NAME', 'Queen Pro'),
        'subject' => 'Upcoming Lead Followup Reminder',
    ],
    'sms' => [
        'provider' => 'twilio',
    ],
    'whatsapp' => [
        'provider' => 'twilio', // or meta
    ],
];

