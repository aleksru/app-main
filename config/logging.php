<?php

use Monolog\Handler\StreamHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'daily'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 3,
            'permission' => 0777,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'custom' => [
            'driver' => 'single',
            'path' => storage_path('logs/custom-laravel.log'),
            'level' => 'debug',
        ],

        'quickrun' => [
            'driver' => 'daily',
            'path' => storage_path('logs/quickrun.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],

        'calls' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calls.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],
        'calls_result_callback' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calls_result_callback.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],
        'calls_route' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calls_route.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],
        'calls_missed' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calls_missed.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],
        'calls_missed_data' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calls_missed_data.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],
        'calls_connected' => [
            'driver' => 'daily',
            'path' => storage_path('logs/calls_connected.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],

        'order-calls' => [
            'driver' => 'daily',
            'path' => storage_path('logs/order-calls.log'),
            'days' => 2,
            'permission' => 0777,
            'level' => 'debug',
        ],

        'api' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api.log'),
            'days' => 2,
            'permission' => 0777,
        ],

        'api_prices' => [
            'driver' => 'daily',
            'path' => storage_path('logs/api_prices.log'),
            'days' => 2,
            'permission' => 0777,
        ],

        'metro' => [
            'driver' => 'single',
            'path' => storage_path('logs/metro.log'),
            'level' => 'debug',
        ],

        'upload_realizations' => [
            'driver' => 'daily',
            'path' => storage_path('logs/uploads/upload_realizations.log'),
            'days' => 2,
            'permission' => 0777,
        ],
        'import_prices' => [
            'driver' => 'daily',
            'path' => storage_path('logs/import_prices.log'),
            'days' => 2,
            'permission' => 0777,
        ],

    ],

];
