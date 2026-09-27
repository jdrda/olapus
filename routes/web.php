<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')
    ->name('root');

Route::get('/home', function () {
    return redirect()->route('admin.dashboard.index');
})->name('home');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
|
| Laravel Fortify registers the login, logout and password reset routes.
| Olapus only supplies the Blade views via FortifyServiceProvider.
|
*/


/*
|--------------------------------------------------------------------------
| Administration
|--------------------------------------------------------------------------
*/

require __DIR__.'/admin.php';
