<?php

//===== Course Routes =====//
Route::get('course/{slug}', 'CoursesController@show')->name('courses.show');
Route::post('course/{course_id}/rating', 'CoursesController@rating')->name('courses.rating');
Route::get('category/{category}/courses', 'CoursesController@getByCategory')->name('courses.category');
Route::post('courses/{id}/review', 'CoursesController@addReview')->name('courses.review');
Route::get('courses/review/{id}/edit', 'CoursesController@editReview')->name('courses.review.edit');
Route::post('courses/review/{id}/edit', 'CoursesController@updateReview')->name('courses.review.update');
Route::get('courses/review/{id}/delete', 'CoursesController@deleteReview')->name('courses.review.delete');

// ==== Search Result ====//
Route::get('search', 'SearchController@searchPage')->name('search.page');
Route::get('search/courses', 'SearchController@courses')->name('courses.search');
Route::get('search/instructors', 'SearchController@teachers')->name('teachers.search');

Route::get('ajax/search/courses/', 'SearchController@searchCourse')->name('ajax.search.course');
Route::get('ajax/search/instructor', 'SearchController@searchInstructor')->name('ajax.search.instructor');

// === Bundle route === //
Route::get('bundle/{slug}', 'BundlesController@show')->name('bundles.show');

// === Profile route === //
Route::get('profile/{uuid}', 'UserController@getTeacherProfile')->name('profile.show');

// === Page route == //
Route::get('pages', 'PageController@index')->name('pages');
Route::get('page/{slug}', 'PageController@getPage')->name('page.show');

Route::group(['middleware' => 'auth'], function () {

    Route::get('course/{course_slug}/{lesson_slug}/{step}', 'LessonsController@show')->name('lessons.show');
    
    // Route::get('quiz/{test_id}/{index}', 'LessonsController@getQuestion')->name('quiz.questions.get');
    // Route::post('quiz/questions/{id}', 'LessonsController@completeQuestion')->name('quiz.complete');
    // Route::get('quiz-result/{test_id}', 'LessonsController@testResult')->name('quiz.result');
    // Route::get('quiz-result/{id}/complete', 'LessonsController@testComplete')->name('quiz.result.complete');

    Route::get('lesson/live/{lesson_slug}/{lesson_id}', 'LessonsController@liveSession')->name('lessons.live');

    Route::get('lesson/{id}/complete', 'LessonsController@completeLesson')->name('lesson.complete');
    Route::get('ajax/step/{id}/complete/{type}', 'LessonsController@completeStep')->name('ajax.step.complete');

    // Assignment
    Route::get('assignment/{id}', 'LessonsController@assignment')->name('lesson.assignment');
    Route::post('assignment/save', 'LessonsController@saveAssignment')->name('assignment.save');

    // Take Quiz for Student
    Route::get('quiz/{lesson_slug}/{quiz_id}', 'StudentController@startQuiz')->name('student.quiz.show');
    Route::post('quiz/save', 'StudentController@saveQuiz')->name('student.quiz.save');
    Route::get('quiz-result/{lesson_slug}/{quiz_id}', 'StudentController@quizResult')->name('student.quiz.result');

    // Cart and Checkout
    Route::post('cart/checkout', 'CartController@checkout')->name('cart.checkout');
    Route::post('cart/add', 'CartController@addToCart')->name('cart.addToCart');

    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::get('cart/remove', 'CartController@remove')->name('cart.remove');
    Route::get('cart/clear', 'CartController@clear')->name('cart.clear');

});

// ==== Course Subscribe ==== //
Route::post('ajax/course/subscribe', 'EnrollController@subscribe')->name('ajax.course.subscribe');