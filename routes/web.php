<?php

use App\Http\Controllers\LanguageController;

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 */
Route::get('/', 'Frontend\HomeController@index')->name('homepage');
Route::get('/user/verify/{remember_token}', 'Auth\RegisterController@verifyUser')->name('user.verify');
Route::post('verfication/resend', 'Auth\RegisterController@resend')->name('verification.resend');

// Super admin login
Route::get('admin', 'Auth\LoginController@showLoginForm');

// Contact Email
Route::get('ajax/email/contact', 'Frontend\PageController@sendContactEmail')->name('ajax.email.contact');

Auth::routes();

// Admin
Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'namespace' => 'Backend', 'middleware' => ['auth', 'profile']], function () {

    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__ . '/backend/');

});

Route::post('account/{id}/update', 'Backend\UserController@updateAccount')->name('admin.myaccount.update');
Route::get('account/{id}/approve', 'Backend\UserController@approveAccount')->name('admin.account.approve');
Route::get('account/{id}/decline', 'Backend\UserController@declineAccount')->name('admin.account.decline');

Route::get('ajax/categories/select', 'Backend\CategoryController@getSelet2Data')->name('admin.select.getCategoriesByAjax');


Route::group(['namespace' => 'Frontend'], function () {
    include_route_files(__DIR__ . '/frontend/');
});