<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Root
 */
Route::get('/', ['as' => 'root', function () {
        return view('welcome');
}]);

/**
 * Admin
 */
Route::group(['prefix' => env('APP_ADMIN_URL', 'admin'), 'middleware' => 'auth'], function () {
    
    /**
     * Main dashboard
     */
    Route::get('/', [
        'as' => 'admin.dashboard.index', 'uses' => 'Admin\DashboardController@index'
    ]);
    
    /**
     * Articles and categories
     */
    Route::resource('page', 'Admin\PageController');
    Route::resource('pageCategory', 'Admin\PageCategoryController');
    
    /**
     * Articles and categories
     */
    Route::resource('article', 'Admin\ArticleController');
    Route::resource('articleCategory', 'Admin\ArticleCategoryController');
    
    /**
     * Feedback
     */
    Route::resource('feedback', 'Admin\FeedbackController');
    
    /**
     * Comment
     */
    Route::resource('comment', 'Admin\CommentController');
    
    /**
     * Sliders and slides
     */
    Route::resource('slider', 'Admin\SliderController');
    Route::resource('slide', 'Admin\SlideController');
    
    /**
     * Images and categories
     */
    Route::resource('image', 'Admin\ImageController', ['middleware' => ['media.addparameters']]);
    Route::resource('imageCategory', 'Admin\ImageCategoryController');
    
     /**
     * Advertising
     */
    Route::resource('advert', 'Admin\AdvertController');
    Route::resource('advertLocation', 'Admin\AdvertLocationController');
    
    /**
     * Settings
     */
    Route::resource('settings', 'Admin\SettingsController', 
            ['except' => ['delete', 'show']]);
    
    /**
     * Users
     */
    Route::resource('user', 'Admin\UserController',
            ['except' => ['show']]);
    
    
});

/**
 * Authentication
 */
Route::group(['prefix' => 'auth'], function () {
    
    Route::get('login', [
        'as' => 'authGetLogin', 'uses' => 'Auth\AuthController@getLogin'
    ]);
    Route::post('login', [
        'as' => 'authPostLogin', 'uses' => 'Auth\AuthController@prePostLogin'
    ]);
    Route::get('logout', [
        'as' => 'authLogout', 'uses' => 'Auth\AuthController@getLogout'
    ]);
    Route::get('password/email', [
        'as' => 'authPasswordEmailGet', 'uses' => 'Auth\PasswordController@getEmail'
    ]);
    Route::post('password/email', [
        'as' => 'authPasswordEmailPost', 'uses' => 'Auth\PasswordController@prePostEmail'
    ]);
    Route::get('password/reset/{token}', [
        'as' => 'authPasswordGetReset', 'uses' => 'Auth\PasswordController@getReset'
    ]);
    Route::post('password/reset', [
        'as' => 'authPasswordPostReset', 'uses' => 'Auth\PasswordController@prePostReset'
    ]);
});

/**
 * Images
 */
Route::get('/' . env('APP_IMAGE_URL', 'images').'/{imageName}.{imageExtension}', [
    'as' => 'getImage', 'uses' => 'Media\ImageController@getImage'
]);


/**
 * Custom routes
 */
require_once('routesCustom.php');
