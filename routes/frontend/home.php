<?php

//===== Course Routes =====//

Route::get('courses', 'CoursesController@all')->name('courses.all');
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

Route::group(['middleware' => 'auth'], function () {

    Route::get('course/{course_slug}/lesson/{lesson_slug}/{step}', 'LessonsController@show')->name('lessons.show');
    Route::get('test/{test_id}/{index}', 'LessonsController@getQuestion')->name('lessons.question');
    Route::post('test/questions/{id}', 'LessonsController@completeQuestion')->name('lessons.complete');
    Route::get('test-result/{test_id}', 'LessonsController@testResult')->name('test.result');
    Route::get('lesson/live/{lesson_slug}/{lesson_id}', 'LessonsController@liveSession')->name('lessons.live');

    Route::post('lesson/{slug}/test', 'LessonsController@test')->name('lessons.test');
    Route::post('lesson/{slug}/retest', 'LessonsController@retest')->name('lessons.retest');
    Route::post('video/progress', 'LessonsController@videoProgress')->name('update.videos.progress');
    Route::post('lesson/progress', 'LessonsController@courseProgress')->name('update.course.progress');
});