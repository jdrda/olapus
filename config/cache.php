<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | Laravel 13 defaults new applications to database cache.
    |
    | Olapus keeps file cache for backwards compatibility and because it
    | requires no cache database table or external service.
    |
    */

    'default' => env('CACHE_STORE', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',

            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),

            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],

            'options' => [
                //
            ],

            'servers' => [
                [
                    'host' => env(
                        'MEMCACHED_HOST',
                        '127.0.0.1'
                    ),
                    'port' => env(
                        'MEMCACHED_PORT',
                        11211
                    ),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env(
                'REDIS_CACHE_CONNECTION',
                'cache'
            ),
            'lock_connection' => env(
                'REDIS_CACHE_LOCK_CONNECTION',
                'default'
            ),
        ],

        'failover' => [
            'driver' => 'failover',
            'stores' => [
                'redis',
                'file',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    */

    'prefix' => env(
        'CACHE_PREFIX',
        Str::slug(
            (string) env('APP_NAME', 'olapus')
        ).'-cache-'
    ),

    /*
    |--------------------------------------------------------------------------
    | Serializable Classes
    |--------------------------------------------------------------------------
    |
    | Laravel 13 disables PHP object unserialization from cache by default
    | to reduce gadget-chain risks if the application key is compromised.
    |
    */

    'serializable_classes' => false,

];