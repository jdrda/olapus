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
 * Laravel default home redirection to admin
 */
Route::get('home', ['as' => 'home', function () {
        return Redirect::route('admin.dashboard.index');
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
     * Articles
     */
    Route::resource('article', 'Admin\ArticleController');
    
    /**
     * Category
     */
    Route::resource('articleCategory', 'Admin\ArticleCategoryController');
    
    /**
     * Slides
     */
    Route::resource('slide', 'Admin\SlideController');
    
    /**
     * Sliders
     */
    Route::resource('slider', 'Admin\SliderController');
    
    /**
     * Images
     */
    Route::resource('image', 'Admin\ImageController', ['middleware' => ['media.addparameters']]);
    
    /**
     * Image categories
     */
    Route::resource('imageCategory', 'Admin\ImageCategoryController');
    
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
        'as' => 'authPostLogin', 'uses' => 'Auth\AuthController@postLogin'
    ]);
    Route::get('logout', [
        'as' => 'authLogout', 'uses' => 'Auth\AuthController@getLogout'
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
