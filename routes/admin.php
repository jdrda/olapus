<?php

use App\Http\Controllers\Admin\AdvertController;
use App\Http\Controllers\Admin\AdvertLocationController;
use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\PageCategoryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UsergroupController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Administration
|--------------------------------------------------------------------------
*/

Route::prefix(config('olapus.admin_url'))
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard.index');


        /*
        |--------------------------------------------------------------------------
        | Pages
        |--------------------------------------------------------------------------
        */

        Route::resource('page', PageController::class);

        Route::resource(
            'pageCategory',
            PageCategoryController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Articles
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'article',
            ArticleController::class
        );

        Route::resource(
            'articleCategory',
            ArticleCategoryController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Feedback
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'feedback',
            FeedbackController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Comments
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'comment',
            CommentController::class
        );

        /*
         * LEGACY:
         * These actions change application state but currently use GET.
         *
         * Keep them temporarily so existing Blade links continue working.
         * Change to PATCH when we migrate the comment views.
         */

        Route::get(
            'comment/{id}/approve',
            [CommentController::class, 'approve']
        )->name('comment.approve');

        Route::get(
            'comment/{id}/spam',
            [CommentController::class, 'spam']
        )->name('comment.spam');


        /*
        |--------------------------------------------------------------------------
        | Sliders
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'slider',
            SliderController::class
        );

        Route::resource(
            'slide',
            SlideController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Advertising
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'advert',
            AdvertController::class
        );

        Route::resource(
            'advertLocation',
            AdvertLocationController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'settings',
            SettingsController::class
        )->except([
            'show',
            'destroy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::get('profile', [UserController::class, 'selfEdit'])
            ->name('profile.edit');

        Route::put('profile', [UserController::class, 'selfUpdate'])
            ->name('profile.update');

        Route::resource(
            'user',
            UserController::class
        )->except([
            'show',
        ]);

        Route::resource(
            'usergroup',
            UsergroupController::class
        )->except([
            'show',
        ]);

        // Generated modules inherit this group's prefix, name and auth middleware.
        require __DIR__.'/admin_generated.php';

        // Optional integration example (multi-column Eloquent relationships).
        require __DIR__.'/admin_composite_example.php';

    });