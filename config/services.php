<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'ses' => [

        'key' => env(
            'AWS_ACCESS_KEY_ID'
        ),

        'secret' => env(
            'AWS_SECRET_ACCESS_KEY'
        ),

        'region' => env(
            'AWS_DEFAULT_REGION',
            'us-east-1'
        ),

        'token' => env(
            'AWS_SESSION_TOKEN'
        ),

    ],

];