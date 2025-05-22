<?php

return [

    'guard' => env('REVERB_GUARD', 'web'),

    'path' => env('REVERB_PATH', '/reverb'),

    'middleware' => [
        'web',
        \Illuminate\Broadcasting\BroadcastMiddleware::class,
        \Laravel\Reverb\Http\Middleware\Authorize::class,
    ],

    'host' => env('REVERB_HOST', '127.0.0.1'),
    'port' => env('REVERB_PORT', 6001),
];