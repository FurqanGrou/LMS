<?php

use App\MonthlyScore;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'dashboard-teachers', 'namespace' => '\App\Http\Controllers\Auth', 'as' => 'teachers.'], function (){
    Route::get('/login', 'TeacherLoginController@showLoginForm')->name('login.form');
    Route::post('/login', 'TeacherLoginController@login')->name('login.post');
});

Route::group(['prefix' => 'dashboard-teachers', 'as' => 'teachers.', 'middleware' => ['auth:teacher_web', 'teacher.status']], function (){

    Route::get('/home', 'TeacherController@index')->name('teacher.index');
    Route::get('/class_students/{class_number}', 'TeacherController@classStudents')->name('classStudents.index');
    Route::post('logout', '\App\Http\Controllers\Auth\TeacherLoginController@logout')->name('logout');

    //    Route::get('/report/create/{id}', 'ReportController@create')->name('report.create');
//    Route::post('/report', 'ReportController@store')->name('report.store');
//    Route::get('/report/{report}/edit', 'ReportController@edit')->name('report.edit');
//    Route::put('/report/{id}/update', 'ReportController@update')->name('report.update');
//    Route::put('/report/{id}/updateTomorrow', 'ReportController@updateTomorrow')->name('report.updateTomorrow');
//    Route::get('/report/absence', 'ReportController@getReportAbsence')->name('report.absence');

    Route::get('send-mail', 'ReportController@sendReport')->name('report.send');

    Route::get('/report/table/{student_id}', 'ReportController@reportTable')->name('report.table');
    Route::post('/report/table/{student_id}', 'ReportController@reportTableStore')->name('report.table');
    Route::post('/report/send/{student_id}', 'ReportController@sendReportTable')->name('send.report');
    Route::post('/report/send-monthly/{student_id}', 'ReportController@sendReportTableMonthly')->name('send.report.monthly');
    Route::post('/report/change_page_number/{student_id}', 'ReportController@changePageNumber')->name('report.changePageNumber');

    Route::get('/classes', 'ClassesController@index')->name('classes.index');
    Route::get('/join_request', 'ClassesController@joinRequest')->name('classes.join_request');

    // change password
    Route::get('/teachers/{teacher}/change-password', 'TeacherController@changePasswordView')->name('change_password.view');
    Route::put('/teachers/{teacher}/change-password', 'TeacherController@changePassword')->name('change_password.post');

    // attendance
    Route::get('/attendance/list', 'AttendanceController@index')->name('attendance.list');
    Route::get('/attendance', 'AttendanceController@create')->name('attendance.index');
    Route::post('/attendance', 'AttendanceController@store')->name('attendance.store');

    // request services
//    Route::get('/request-services/{form}', 'RequestServiceController@createExamRequest')->name('request_services.form');
//    Route::post('/request-services', 'RequestServiceController@store')->name('request_services.store');
//
//    Route::get('/request-services/exams', 'RequestServiceController@exam')->name('request_services.form');
//    Route::get('/request-services/exams/show/{service}', 'RequestServiceController@showExam')->name('request_services.show_exam');

    Route::get('/request-services/excuse', 'SuggestComplaintBoxController@index')->name('request_services.index');
    Route::get('/request-services/excuse/create', 'SuggestComplaintBoxController@create')->name('request_services.create');
    Route::post('/request-services/excuse', 'SuggestComplaintBoxController@store')->name('request_services.store');

    Route::get('/request-services/exam', 'ExamRequestController@index')->name('request_services.exam.index');
    Route::get('/request-services/exam/create', 'ExamRequestController@create')->name('request_services.exam.create');
    Route::post('/request-services/exam', 'ExamRequestController@store')->name('request_services.exam.store');

});

Route::group(['prefix' => 'dashboard-teachers', 'namespace' => '\App\Http\Controllers\Auth'], function (){
    Route::get('/login', 'TeacherLoginController@showLoginForm')->name('dashboard.login.form');
});
