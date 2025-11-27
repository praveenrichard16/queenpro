<?php

return [
    'stage_weights' => [
        'won' => 100,
        'lost' => 10,
        'open' => 40,
    ],

    'source_weights' => [
        'referral' => 15,
        'website' => 10,
        'social' => 8,
        'event' => 12,
    ],

    'expected_value' => [
        'divisor' => 1000,
        'max_weight' => 25,
    ],

    'activity' => [
        'recent_days' => 14,
        'weight_per_activity' => 5,
        'max_weight' => 20,
    ],

    'followups' => [
        'upcoming_window_hours' => 48,
        'upcoming_bonus' => 10,
        'overdue_penalty' => 15,
    ],
];

