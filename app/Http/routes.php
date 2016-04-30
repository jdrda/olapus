<?php
/**
 * Main router file
 * 
 * Basic route definition
 * 
 * @category Router
 * @subpackage General
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
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
Route::group(['prefix' => env('APP_ADMIN_URL', 'admin'), 'as' => 'admin.', 'middleware' => 'auth'], function () {
    
    /**
     * Main dashboard
     */
    Route::get('/', [
        'as' => 'dashboard.index', 'uses' => 'Admin\DashboardController@index'
    ]);
    
    /**
     * Articles and categories
     */
    Route::resource('page', 'Admin\PageController', ['names' => [
        'index' => 'page.index',
        'create' => 'page.create',
        'store' => 'page.store',
        'show' => 'page.show',
        'edit' => 'page.edit',
        'update' => 'page.update',
        'destroy' => 'page.destroy'
        ]]);
    Route::resource('pageCategory', 'Admin\PageCategoryController', ['names' => [
        'index' => 'pageCategory.index',
        'create' => 'pageCategory.create',
        'store' => 'pageCategory.store',
        'show' => 'pageCategory.show',
        'edit' => 'pageCategory.edit',
        'update' => 'pageCategory.update',
        'destroy' => 'pageCategory.destroy'
        ]]);
    
    /**
     * Articles and categories
     */
    Route::resource('article', 'Admin\ArticleController', ['names' => [
        'index' => 'article.index',
        'create' => 'article.create',
        'store' => 'article.store',
        'show' => 'article.show',
        'edit' => 'article.edit',
        'update' => 'article.update',
        'destroy' => 'article.destroy'
        ]]);
    Route::resource('articleCategory', 'Admin\ArticleCategoryController', ['names' => [
        'index' => 'articleCategory.index',
        'create' => 'articleCategory.create',
        'store' => 'articleCategory.store',
        'show' => 'articleCategory.show',
        'edit' => 'articleCategory.edit',
        'update' => 'articleCategory.update',
        'destroy' => 'articleCategory.destroy'
        ]]);
    
    /**
     * Feedback
     */
    Route::resource('feedback', 'Admin\FeedbackController', ['names' => [
        'index' => 'feedback.index',
        'create' => 'feedback.create',
        'store' => 'feedback.store',
        'show' => 'feedback.show',
        'edit' => 'feedback.edit',
        'update' => 'feedback.update',
        'destroy' => 'feedback.destroy'
        ]]);
    
    /**
     * Comment
     */
    Route::resource('comment', 'Admin\CommentController', ['names' => [
        'index' => 'comment.index',
        'create' => 'comment.create',
        'store' => 'comment.store',
        'show' => 'comment.show',
        'edit' => 'comment.edit',
        'update' => 'comment.update',
        'destroy' => 'comment.destroy'
        ]]);
    Route::get('comment/{id}/approve', [
        'as' => 'comment.approve', 'uses' => 'Admin\CommentController@approve'
    ]);
    Route::get('comment/{id}/spam', [
        'as' => 'comment.spam', 'uses' => 'Admin\CommentController@spam'
    ]);
    
    /**
     * Sliders and slides
     */
    Route::resource('slider', 'Admin\SliderController', ['names' => [
        'index' => 'slider.index',
        'create' => 'slider.create',
        'store' => 'slider.store',
        'show' => 'slider.show',
        'edit' => 'slider.edit',
        'update' => 'slider.update',
        'destroy' => 'slider.destroy'
        ]]);
    Route::resource('slide', 'Admin\SlideController', ['names' => [
        'index' => 'slide.index',
        'create' => 'slide.create',
        'store' => 'slide.store',
        'show' => 'slide.show',
        'edit' => 'slide.edit',
        'update' => 'slide.update',
        'destroy' => 'slide.destroy'
        ]]);
    
    /**
     * Images and categories
     */
    Route::resource('image', 'Admin\ImageController', ['middleware' => ['media.addparameters'],
        'names' => [
        'index' => 'image.index',
        'create' => 'image.create',
        'store' => 'image.store',
        'show' => 'image.show',
        'edit' => 'image.edit',
        'update' => 'image.update',
        'destroy' => 'image.destroy'
        ]]);
    Route::resource('imageCategory', 'Admin\ImageCategoryController', ['names' => [
        'index' => 'imageCategory.index',
        'create' => 'imageCategory.create',
        'store' => 'imageCategory.store',
        'show' => 'imageCategory.show',
        'edit' => 'imageCategory.edit',
        'update' => 'imageCategory.update',
        'destroy' => 'imageCategory.destroy'
        ]]);
    
     /**
     * Advertising
     */
    Route::resource('advert', 'Admin\AdvertController', ['names' => [
        'index' => 'advert.index',
        'create' => 'advert.create',
        'store' => 'advert.store',
        'show' => 'advert.show',
        'edit' => 'advert.edit',
        'update' => 'advert.update',
        'destroy' => 'advert.destroy'
        ]]);
    Route::resource('advertLocation', 'Admin\AdvertLocationController', ['names' => [
        'index' => 'advertLocation.index',
        'create' => 'advertLocation.create',
        'store' => 'advertLocation.store',
        'show' => 'advertLocation.show',
        'edit' => 'advertLocation.edit',
        'update' => 'advertLocation.update',
        'destroy' => 'advertLocation.destroy'
        ]]);
    
    /**
     * Products and categories
     */
    Route::resource('productCategory', 'Admin\ProductCategoryController', ['names' => [
        'index' => 'productCategory.index',
        'create' => 'productCategory.create',
        'store' => 'productCategory.store',
        'show' => 'productCategory.show',
        'edit' => 'productCategory.edit',
        'update' => 'productCategory.update',
        'destroy' => 'productCategory.destroy'
        ]]);
    Route::resource('product', 'Admin\ProductController', ['names' => [
        'index' => 'product.index',
        'create' => 'product.create',
        'store' => 'product.store',
        'show' => 'product.show',
        'edit' => 'product.edit',
        'update' => 'product.update',
        'destroy' => 'product.destroy'
        ]]);
    
    /**
     * Settings
     */
    Route::resource('settings', 'Admin\SettingsController', [
        'except' => ['delete', 'show'],
        'names' => [
        'index' => 'settings.index',
        'create' => 'settings.create',
        'store' => 'settings.store',
        'show' => 'settings.show',
        'edit' => 'settings.edit',
        'update' => 'settings.update',
        'destroy' => 'settings.destroy'
        ]]);
    
    /**
     * Users
     */
    Route::resource('user', 'Admin\UserController', [
        'except' => ['show'],
        'names' => [
        'index' => 'user.index',
        'create' => 'user.create',
        'store' => 'user.store',
        'show' => 'user.show',
        'edit' => 'user.edit',
        'update' => 'user.update',
        'destroy' => 'user.destroy'
        ]]);
    
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