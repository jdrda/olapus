<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */

    'default' => env(
        'FILESYSTEM_DISK',
        'local'
    ),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    */

    'disks' => [

        /*
         * Compatibility with existing Olapus files.
         *
         * Modern Laravel normally uses storage/app/private here.
         */
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',

            'root' => storage_path(
                'app/public'
            ),

            'url' => rtrim(
                    env('APP_URL', 'http://localhost'),
                    '/'
                ).'/storage',

            'visibility' => 'public',

            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',

            'key' => env(
                'AWS_ACCESS_KEY_ID'
            ),

            'secret' => env(
                'AWS_SECRET_ACCESS_KEY'
            ),

            'region' => env(
                'AWS_DEFAULT_REGION'
            ),

            'bucket' => env(
                'AWS_BUCKET'
            ),

            'url' => env(
                'AWS_URL'
            ),

            'endpoint' => env(
                'AWS_ENDPOINT'
            ),

            'use_path_style_endpoint' => env(
                'AWS_USE_PATH_STYLE_ENDPOINT',
                false
            ),

            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */

    'links' => [
        public_path('storage')
        => storage_path('app/public'),
    ],

];