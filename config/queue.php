<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection
    |--------------------------------------------------------------------------
    |
    | Keep synchronous processing as the default for existing Olapus
    | installations. No queue worker is required.
    |
    */

    'default' => env(
        'QUEUE_CONNECTION',
        'sync'
    ),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',

            'connection' => env(
                'DB_QUEUE_CONNECTION'
            ),

            'table' => env(
                'DB_QUEUE_TABLE',
                'jobs'
            ),

            'queue' => env(
                'DB_QUEUE',
                'default'
            ),

            'retry_after' => (int) env(
                'DB_QUEUE_RETRY_AFTER',
                90
            ),

            'after_commit' => false,
        ],

        'sqs' => [
            'driver' => 'sqs',

            'key' => env(
                'AWS_ACCESS_KEY_ID'
            ),

            'secret' => env(
                'AWS_SECRET_ACCESS_KEY'
            ),

            'prefix' => env(
                'SQS_PREFIX'
            ),

            'queue' => env(
                'SQS_QUEUE',
                'default'
            ),

            'suffix' => env(
                'SQS_SUFFIX'
            ),

            'region' => env(
                'AWS_DEFAULT_REGION',
                'us-east-1'
            ),

            'after_commit' => false,
        ],

        'redis' => [
            'driver' => 'redis',

            'connection' => env(
                'REDIS_QUEUE_CONNECTION',
                'default'
            ),

            'queue' => env(
                'REDIS_QUEUE',
                'default'
            ),

            'retry_after' => (int) env(
                'REDIS_QUEUE_RETRY_AFTER',
                90
            ),

            'block_for' => null,

            'after_commit' => false,
        ],

        /*
         * Laravel 13 can defer a job until after the current HTTP response.
         */
        'deferred' => [
            'driver' => 'deferred',
        ],

        /*
         * Runs the job in a separate background process.
         */
        'background' => [
            'driver' => 'background',
        ],

        'failover' => [
            'driver' => 'failover',

            'connections' => [
                'database',
                'deferred',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Job Batching
    |--------------------------------------------------------------------------
    */

    'batching' => [
        'database' => env(
            'DB_CONNECTION',
            'mysql'
        ),

        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | Olapus currently defaults to sync queues. We therefore do not require
    | a failed_jobs database table just to run the application.
    |
    | When asynchronous queues are introduced, change the default to
    | database-uuids and create Laravel's queue tables.
    |
    */

    'failed' => [

        'driver' => env(
            'QUEUE_FAILED_DRIVER',
            'null'
        ),

        'database' => env(
            'DB_CONNECTION',
            'mysql'
        ),

        'table' => 'failed_jobs',

    ],

];