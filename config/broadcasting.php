<?php

return [
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => false,
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 6001),
        'scheme' => env('PUSHER_SCHEME', 'http'),
        'encrypted' => false,
    ],
];
