<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Oneclick v1
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | You can set the routes for Laravel Oneclick
    | Prefix and name, are available
    |
    */

    'prefix' => env('ONECLICK_PREFIX', 'oneclick'),
    'name' => env('ONECLICK_NAME', 'oneclick.'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | You can set the environment for Laravel Oneclick
    | Commerce code, secret key, and debug, are available
    | Please take care with the debug option
    |
    */

    'in_production' => env('ONECLICK_IN_PRODUCTION', false),
    'api_key' => env('ONECLICK_API_KEY'),
    'mall_code' => env('ONECLICK_MALL_CODE'),
    'commerce_code' => env('ONECLICK_COMMERCE_CODE'),
    'debug' => env('ONECLICK_DEBUG', false),

    // Optional fields

    'texts' => [
        'creating' => [
            'content' => 'Redirecting...',
            'title' => 'Redirecting to Oneclick',
        ],
        'retry' => [
            'content' => 'Please try again',
            'title' => 'Retry payment',
        ],
    ],
];
