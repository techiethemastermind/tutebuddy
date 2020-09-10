<?php

//===== Course Routes =====//
Route::get('course/{slug}', 'CoursesController@show')->name('courses.show');
Route::post('course/{course_id}/rating', 'CoursesController@rating')->name('courses.rating');
Route::get('category/{category}/courses', 'CoursesController@getByCategory')->name('courses.category');
Route::post('courses/{id}/review', 'CoursesController@addReview')->name('courses.review');
Route::get('courses/review/{id}/edit', 'CoursesController@editReview')->name('courses.review.edit');
Route::post('courses/review/{id}/edit', 'CoursesController@updateReview')->name('courses.review.update');
Route::get('courses/review/{id}/delete', 'CoursesController@deleteReview')->name('courses.review.delete');

// ==== Ajax for Search Form ==== //
Route::get('ajax/courses/search/{key}', 'CoursesController@getSearchFormData')->name('ajax.search.form');

// ==== Search Result ====//
Route::get('search', 'CoursesController@searchPage')->name('search.page');

// === Bundle route === //
Route::get('bundle/{slug}', 'BundlesController@show')->name('bundles.show');

Route::group(['middleware' => 'auth'], function () {

    Route::get('courses/search', 'CoursesController@search')->name('courses.search');

    Route::get('course/{course_slug}/lesson/{lesson_slug}/{step}', 'LessonsController@show')->name('lessons.show');
    Route::get('test/{test_id}/{index}', 'LessonsController@getQuestion')->name('test.questions.get');
    Route::post('test/questions/{id}', 'LessonsController@completeQuestion')->name('test.complete');
    Route::get('test-result/{test_id}', 'LessonsController@testResult')->name('test.result');
    Route::get('test-result/{id}/complete', 'LessonsController@testComplete')->name('test.result.complete');

    Route::get('lesson/live/{lesson_slug}/{lesson_id}', 'LessonsController@liveSession')->name('lessons.live');

    Route::get('lesson/{id}/complete', 'LessonsController@completeLesson')->name('lesson.complete');
    Route::get('ajax/step/{id}/complete/{type}', 'LessonsController@completeStep')->name('ajax.step.complete');

    Route::post('lesson/{slug}/test', 'LessonsController@test')->name('lessons.test');
    Route::post('lesson/{slug}/retest', 'LessonsController@retest')->name('lessons.retest');
    Route::post('video/progress', 'LessonsController@videoProgress')->name('update.videos.progress');
    Route::post('lesson/progress', 'LessonsController@courseProgress')->name('update.course.progress');

    // Cart and Checkout
    Route::post('cart/checkout', 'CartController@checkout')->name('cart.checkout');
    Route::post('cart/add', 'CartController@addToCart')->name('cart.addToCart');

    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::get('cart/remove', 'CartController@remove')->name('cart.remove');
    Route::get('cart/clear', 'CartController@clear')->name('cart.clear');

});

// ==== Course Subscribe ==== //
Route::post('ajax/course/subscribe', 'EnrollController@subscribe')->name('ajax.course.subscribe');