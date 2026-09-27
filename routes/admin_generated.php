<?php

use Illuminate\Support\Facades\Route;

// Loaded inside the auth-protected admin group in routes/admin.php.
// make:admin-module appends only to this dedicated file.
Route::resource('project', \App\Http\Controllers\Admin\ProjectController::class)->except('show');
Route::resource('projectLabel', \App\Http\Controllers\Admin\ProjectLabelController::class)->except('show');
Route::resource('projectTask', \App\Http\Controllers\Admin\ProjectTaskController::class)->except('show');
