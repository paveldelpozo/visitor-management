<?php

return [

    'app_id' => env('REVERB_APP_ID', '00000000'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Host
    |--------------------------------------------------------------------------
    |
    | La dirección donde se ejecuta el servidor WebSocket. En local suele ser
    | "127.0.0.1" o "0.0.0.0". Cambia esto si ejecutas en producción o contenedores.
    |
    */

    'host' => env('REVERB_HOST', '0.0.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Port
    |--------------------------------------------------------------------------
    |
    | Puerto donde se escucha el servidor WebSocket. Por defecto se recomienda 6001.
    |
    */

    'port' => env('REVERB_PORT', 6001),

    /*
    |--------------------------------------------------------------------------
    | Reverb Path
    |--------------------------------------------------------------------------
    |
    | Ruta pública del WebSocket. Normalmente es "/app" para mantener compatibilidad
    | con el cliente de Pusher.
    |
    */

    'path' => env('REVERB_PATH', '/reverb'),

    'allowed_origins' => ['localhost', '127.0.0.1', env('LOCAL_IP', '0.0.0.0')],

    /*
    |--------------------------------------------------------------------------
    | Internal Path
    |--------------------------------------------------------------------------
    |
    | La ruta interna que utiliza el servidor para manejar los WebSockets.
    | No suele ser necesario cambiarla.
    |
    */

    'internal_path' => '/reverb',

    'guard' => env('REVERB_GUARD', 'web'),

    'middleware' => [
        'web',
        'auth:sanctum',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Puedes activar o desactivar el log detallado del servidor Reverb.
    |
    */

    'log' => env('REVERB_LOG', true),
];
