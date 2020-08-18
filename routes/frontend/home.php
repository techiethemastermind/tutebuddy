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


Route::group(['middleware' => 'auth'], function () {

    Route::get('lesson/{course_slug}/{slug}/{step}', 'LessonsController@show')->name('lessons.show');
    Route::post('lesson/{slug}/test', 'LessonsController@test')->name('lessons.test');
    Route::post('lesson/{slug}/retest', 'LessonsController@retest')->name('lessons.retest');
    Route::post('video/progress', 'LessonsController@videoProgress')->name('update.videos.progress');
    Route::post('lesson/progress', 'LessonsController@courseProgress')->name('update.course.progress');
});