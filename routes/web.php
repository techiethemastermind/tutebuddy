<?php

/*
 * Frontend Routes
 */
Route::get('/', 'Frontend\HomeController@index')->name('homepage');
Route::get('/user/verify/{remember_token}', 'Auth\RegisterController@verifyUser')->name('user.verify');

Auth::routes();

// Admin
Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'namespace' => 'Backend', 'middleware' => ['auth']], function () {

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

Route::group(['namespace' => 'Frontend'], function () {
    include_route_files(__DIR__ . '/frontend/');
});