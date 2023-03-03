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
            'auth' => 'https://lk.cm.expert/oauth/token',
            'stock' => 'https://lk.cm.expert/api/v1/dealers/dms/cars',
            'operations' => 'https://lk.cm.expert/api/v1/dealers/{dealerId}/dms/cars/{dmsCarId}/planned-operations',
            'appraisal' => 'https://lk.cm.expert/api/v1/cars/appraisals?filter[id]={id}',
            'placements' => 'https://lk.cm.expert/api/v1/dealers/dms/placements?filter[dmsCarId]={dmsCarId}'
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    |
    | Request options
    |
    */
    'request' => [
        'perPage' => 50,
    ]
];
