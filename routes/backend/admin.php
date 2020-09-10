<?php

/*
 * All route names are prefixed with 'admin.'.
 */

//===== Users Routes =====//
Route::resource('users','UserController');
Route::get('account', 'UserController@myAccount')->name('myaccount');
Route::post('account/{id}/update', 'UserController@updateAccount')->name('myaccount.update');
Route::post('child-account', 'UserController@childAccount')->name('myaccount.child');

//===== Roles Routes =====//
Route::resource('roles','RoleController');

//===== Dashboard Routes =====//
Route::get('/', 'DashboardController@index')->name('dashboard');

//===== Categories Routes =====//
Route::resource('categories', 'CategoryController');
Route::get('ajax/categories/table', 'CategoryController@getTableData')->name('table.getCategoriesByAjax');
Route::get('ajax/categories/select', 'CategoryController@getSelet2Data')->name('select.getCategoriesByAjax');

//===== Levels Routes =====//
Route::resource('levels', 'LevelController');
Route::get('get/levels/{category_id}', 'LevelController@getLevelsByCategory')->name('getLevelsByCategory');
Route::get('ajax/levels/list', 'LevelController@getList')->name('getLevelsByAjax');

//===== Course types Routes =====//
Route::resource('types', 'TypeController');
Route::get('ajax/types/list', 'TypeController@getList')->name('getTypesByAjax');

//===== Media Library Routes =====//
Route::resource('media', 'MediaController');

//===== Courses Routes =====//
Route::resource('courses', 'CourseController');
Route::get('courses/restore/{id}', 'CourseController@restore')->name('courses.restore');
Route::get('ajax/courses/list/{type}', 'CourseController@getList')->name('getCoursesByAjax');
Route::get('ajax/courses/publish/{id}', 'CourseController@publish')->name('courses.publish');

//===== Bundles Routes =====//
Route::resource('bundles', 'BundlesController');
Route::get('bundles/restore/{id}', 'BundlesController@restore')->name('bundle.restore');
Route::get('ajax/bundles/list/{type}', 'BundlesController@getList')->name('getBundlesByAjax');
Route::get('ajax/bundles/publish/{id}', 'BundlesController@publish')->name('bundle.publish');

//===== Assignment Routes =====//
Route::resource('assignments', 'AssignmentsController');
Route::get('assignments/restore/{id}', 'AssignmentsController@restore')->name('assignment.restore');
Route::get('ajax/assignments/list/{type}', 'AssignmentsController@getList')->name('getAssignmentsByAjax');
Route::get('ajax/assignments/publish/{id}', 'AssignmentsController@publish')->name('assignment.publish');
Route::get('ajax/assignments/lessons', 'AssignmentsController@getLessons')->name('assignment.getLessonsByCourse');

//===== Lessons Routes =====//
Route::resource('lessons', 'LessonController');
Route::get('lessons/delete/{id}', 'LessonController@deleteLesson')->name('lessons.delete');
Route::get('lessons/lesson/{id}', 'LessonController@getLesson')->name('lesson.getById');
Route::get('steps/delete/{id}', 'LessonController@deleteStep')->name('steps.delete');

//===== Schedule Routes ====//
Route::get('schedule', 'ScheduleController@index')->name('schedule');
Route::get('schedule/source', 'ScheduleController@getScheduleData')->name('getScheduleData');
Route::post('schedule/new', 'ScheduleController@storeSchedule')->name('storeSchedule');
Route::post('schedule/lesson/add', 'ScheduleController@addLesson')->name('addLesson');
Route::post('schedule/update', 'ScheduleController@updateSchedule')->name('updateSchedule');
Route::get('schedule/delete', 'ScheduleController@deleteSchedule')->name('removeSchedule');

Route::get('get/course/lessons', 'ScheduleController@getLessons')->name('getLessonsByCourse');

//==== Learn Routes ====//
Route::get('learn/course/{id}', 'LearnController@course')->name('learn.course');

//===== Test Routes =====//
Route::resource('tests', 'TestController');
Route::get('ajax/tests/list/{course_id}', 'TestController@getList')->name('getTestsByAjax');

//===== Questions Routes =====//
Route::resource('questions', 'QuestionController');
Route::get('ajax/questions/list/{course_id}/{test_id}', 'QuestionController@getList')->name('getQuestionsByAjax');
Route::get('questions/delete/{id}', 'QuestionController@delete')->name('questions.delete');
Route::get('questions/restore/{id}', 'QuestionController@restore')->name('questions.restore');

//===== Questions Options Routes =====//
Route::resource('questions_options', 'QuestionOptionsController');
Route::get('questions_options/delete/{id}', 'QuestionOptionsController@delete')->name('questions_options.delete');


//==== Settings Route ====//
Route::get('settings/general', 'ConfigController@getGeneralSettings')->name('settings.general');
Route::post('settings/general', 'ConfigController@saveGeneralSettings')->name('settings.general.save');

// Review
Route::resource('reviews', 'ReviewController');
Route::get('ajax/reviews/{id}/publish', 'ReviewController@publish')->name('publishByAjax');
Route::get('ajax/reviews/list', 'ReviewController@getTableData')->name('getReviewsByAjax');

// Certificate
Route::get('certificates', 'CertificateController@index')->name('certificates.index');
Route::get('ajax/certificates', 'CertificateController@getCertificates')->name('table.getCertsByAjax');
Route::post('certificates/generate', 'CertificateController@generateCertificate')->name('certificates.generate');
Route::get('certificates/download', ['uses' => 'CertificateController@download', 'as' => 'certificates.download']);