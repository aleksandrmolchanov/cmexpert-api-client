<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server configuration
    |--------------------------------------------------------------------------
    |
    | Server configuration options
    |
    */
    'server' => [
        'urls' => [
            'auth' => 'https://lk.cm.expert/oauth/token'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Client configuration
    |--------------------------------------------------------------------------
    |
    | Client configuration options
    |
    */
    'client' => [
        'id' => env('CLIENT_ID'),
        'secret' => env('CLIENT_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Connection
    |--------------------------------------------------------------------------
    |
    | Connection options
    |
    */
    'connection' => [
        'retries' => [
            'times' => 3,
            'sleep' => 5000
        ]
    ]
];
