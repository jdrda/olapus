<?php

use App\Models\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Olapus uses Laravel's standard session based "web" guard.
    | This is also what the existing Laravel UI authentication controllers
    | expect.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Web authentication is session based.
    |
    | No separate "api" token guard is defined here. If Olapus needs API
    | authentication, Laravel Sanctum should be used via auth:sanctum.
    |
    */

    'guards' => [

        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Keep App\User for now because that is the model used by the existing
    | Olapus application. Moving it to App\Models\User can be done separately.
    |
    */

    'providers' => [

        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Laravel 13 normally uses the password_reset_tokens table.
    |
    | Olapus historically uses password_resets, so we intentionally preserve
    | that table for now. The old table schema is sufficient for Laravel's
    | password broker and avoids an unnecessary database migration.
    |
    */

    'passwords' => [

        'users' => [
            'provider' => 'users',
            'table' => env(
                'AUTH_PASSWORD_RESET_TOKEN_TABLE',
                'password_resets'
            ),
            'expire' => 60,
            'throttle' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env(
        'AUTH_PASSWORD_TIMEOUT',
        10800
    ),

];
