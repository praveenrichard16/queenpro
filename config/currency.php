<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the default currency settings for the application.
    | The currency can be overridden using the Setting model if needed.
    |
    */

    'symbol' => env('CURRENCY_SYMBOL', '₹'),
    'code' => env('CURRENCY_CODE', 'INR'),
    'locale' => env('CURRENCY_LOCALE', 'en_IN'),
    'decimals' => env('CURRENCY_DECIMALS', 2),
];

