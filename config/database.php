<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection
    |--------------------------------------------------------------------------
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        /*
        |--------------------------------------------------------------------------
        | MySQL
        |--------------------------------------------------------------------------
        */

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),

            'host' => env(
                'DB_HOST',
                '127.0.0.1'
            ),

            'port' => env(
                'DB_PORT',
                '3306'
            ),

            'database' => env(
                'DB_DATABASE',
                'olapus'
            ),

            'username' => env(
                'DB_USERNAME',
                'root'
            ),

            'password' => env(
                'DB_PASSWORD',
                ''
            ),

            'unix_socket' => env(
                'DB_SOCKET',
                ''
            ),

            'charset' => env(
                'DB_CHARSET',
                'utf8mb4'
            ),

            'collation' => env(
                'DB_COLLATION',
                'utf8mb4_unicode_ci'
            ),

            'prefix' => '',

            'prefix_indexes' => true,

            /*
             * Keep SQL strict mode enabled.
             *
             * If an old Olapus query fails because of this, fix the query
             * instead of globally disabling MySQL validation.
             */
            'strict' => true,

            'engine' => null,

            'options' => extension_loaded('pdo_mysql')
                ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA =>
                        env('MYSQL_ATTR_SSL_CA'),
                ])
                : [],
        ],

        /*
        |--------------------------------------------------------------------------
        | SQLite
        |--------------------------------------------------------------------------
        |
        | Useful for tests and small local tools even though Olapus normally
        | uses MySQL.
        |
        */

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),

            'database' => env(
                'DB_DATABASE_SQLITE',
                database_path('database.sqlite')
            ),

            'prefix' => '',

            'foreign_key_constraints' => env(
                'DB_FOREIGN_KEYS',
                true
            ),

            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
            'transaction_mode' => 'DEFERRED',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis
    |--------------------------------------------------------------------------
    */

    'redis' => [

        'client' => env(
            'REDIS_CLIENT',
            'phpredis'
        ),

        'options' => [

            'cluster' => env(
                'REDIS_CLUSTER',
                'redis'
            ),

            'prefix' => env(
                'REDIS_PREFIX',
                Str::slug(
                    (string) env('APP_NAME', 'olapus')
                ).'-database-'
            ),

            'persistent' => env(
                'REDIS_PERSISTENT',
                false
            ),
        ],

        'default' => [

            'url' => env('REDIS_URL'),

            'host' => env(
                'REDIS_HOST',
                '127.0.0.1'
            ),

            'username' => env(
                'REDIS_USERNAME'
            ),

            'password' => env(
                'REDIS_PASSWORD'
            ),

            'port' => env(
                'REDIS_PORT',
                '6379'
            ),

            'database' => env(
                'REDIS_DB',
                '0'
            ),

            'max_retries' => env(
                'REDIS_MAX_RETRIES',
                3
            ),

            'backoff_algorithm' => env(
                'REDIS_BACKOFF_ALGORITHM',
                'decorrelated_jitter'
            ),

            'backoff_base' => env(
                'REDIS_BACKOFF_BASE',
                100
            ),

            'backoff_cap' => env(
                'REDIS_BACKOFF_CAP',
                1000
            ),
        ],

        'cache' => [

            'url' => env('REDIS_URL'),

            'host' => env(
                'REDIS_HOST',
                '127.0.0.1'
            ),

            'username' => env(
                'REDIS_USERNAME'
            ),

            'password' => env(
                'REDIS_PASSWORD'
            ),

            'port' => env(
                'REDIS_PORT',
                '6379'
            ),

            'database' => env(
                'REDIS_CACHE_DB',
                '1'
            ),

            'max_retries' => env(
                'REDIS_MAX_RETRIES',
                3
            ),

            'backoff_algorithm' => env(
                'REDIS_BACKOFF_ALGORITHM',
                'decorrelated_jitter'
            ),

            'backoff_base' => env(
                'REDIS_BACKOFF_BASE',
                100
            ),

            'backoff_cap' => env(
                'REDIS_BACKOFF_CAP',
                1000
            ),
        ],

    ],

];