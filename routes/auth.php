<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get(
    'login',
    [LoginController::class, 'showLoginForm']
)->name('login');


Route::post(
    'login',
    [LoginController::class, 'login']
)
    ->middleware([
        ProtectAgainstSpam::class,
        'throttle:5,1',
    ])
    ->name('authPostLogin');


/*
|--------------------------------------------------------------------------
| Password reset
|--------------------------------------------------------------------------
*/

Route::get(
    'password/reset',
    [ForgotPasswordController::class, 'showLinkRequestForm']
)->name('authPasswordEmailGet');


Route::post(
    'password/email',
    [ForgotPasswordController::class, 'sendResetLinkEmail']
)
    ->middleware([
        ProtectAgainstSpam::class,
        'throttle:5,1',
    ])
    ->name('authPasswordEmailPost');


Route::get(
    'password/reset/{token}',
    [ResetPasswordController::class, 'showResetForm']
)->name('authPasswordResetGet');


Route::post(
    'password/reset',
    [ResetPasswordController::class, 'reset']
)
    ->middleware([
        ProtectAgainstSpam::class,
        'throttle:5,1',
    ])
    ->name('authPasswordPostReset');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
|
| LEGACY:
| Route::any() is preserved temporarily because the existing administration
| may still log users out using a normal <a href=""> link.
|
| Target state: POST-only logout protected by CSRF.
|
*/

Route::post(
    'logout',
    [LoginController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');