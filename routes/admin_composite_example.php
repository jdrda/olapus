<?php

use App\Http\Controllers\Admin\ExternalRecordController;
use App\Http\Controllers\Admin\ExternalRowController;
use Illuminate\Support\Facades\Route;

// Included INSIDE the existing admin/auth group. Requires awobaz/compoships.
Route::resource('externalRecord', ExternalRecordController::class)->except('show');
Route::resource('externalRow', ExternalRowController::class)->except('show');
