<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    */

    'default' => env(
        'MAIL_MAILER',
        'log'
    ),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',

            'scheme' => env(
                'MAIL_SCHEME'
            ),

            'url' => env(
                'MAIL_URL'
            ),

            'host' => env(
                'MAIL_HOST',
                '127.0.0.1'
            ),

            'port' => env(
                'MAIL_PORT',
                2525
            ),

            'username' => env(
                'MAIL_USERNAME'
            ),

            'password' => env(
                'MAIL_PASSWORD'
            ),

            'timeout' => null,

            'local_domain' => env(
                'MAIL_EHLO_DOMAIN',
                parse_url(
                    (string) env(
                        'APP_URL',
                        'http://localhost'
                    ),
                    PHP_URL_HOST
                )
            ),
        ],

        /*
         * Available if you decide to use AWS SES.
         */
        'ses' => [
            'transport' => 'ses',
        ],

        'sendmail' => [
            'transport' => 'sendmail',

            'path' => env(
                'MAIL_SENDMAIL_PATH',
                '/usr/sbin/sendmail -bs -i'
            ),
        ],

        /*
         * Useful for local development:
         * messages are written to the configured log.
         */
        'log' => [
            'transport' => 'log',
            'channel' => env(
                'MAIL_LOG_CHANNEL'
            ),
        ],

        /*
         * Keeps messages in memory. Useful primarily for tests.
         */
        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',

            'mailers' => [
                'smtp',
                'log',
            ],

            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global From Address
    |--------------------------------------------------------------------------
    */

    'from' => [

        'address' => env(
            'MAIL_FROM_ADDRESS',
            'hello@example.com'
        ),

        'name' => env(
            'MAIL_FROM_NAME',
            env('APP_NAME', 'Olapus')
        ),

    ],

];