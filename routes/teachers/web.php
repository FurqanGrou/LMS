<?php

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
    Route::get('/report/create/{id}', 'ReportController@create')->name('report.create');
    Route::post('/report', 'ReportController@store')->name('report.store');
    Route::get('/report/{report}/edit', 'ReportController@edit')->name('report.edit');
    Route::put('/report/{id}/update', 'ReportController@update')->name('report.update');
    Route::put('/report/{id}/updateTomorrow', 'ReportController@updateTomorrow')->name('report.updateTomorrow');
    Route::get('/report/absence', 'ReportController@getReportAbsence')->name('report.absence');

    Route::get('send-mail', 'ReportController@sendReport')->name('report.send');

    Route::get('/report/table/{student_id}', 'ReportController@reportTable')->name('report.table');
    Route::post('/report/table/{student_id}', 'ReportController@reportTableStore')->name('report.table');
    Route::post('/report/send/{student_id}', 'ReportController@sendReportTable')->name('send.report');

    Route::get('/classes', 'ClassesController@index')->name('classes.index');
    Route::get('/join_request', 'ClassesController@joinRequest')->name('classes.join_request');

});

Route::group(['prefix' => 'dashboard-teachers', 'namespace' => '\App\Http\Controllers\Auth'], function (){
    Route::get('/login', 'TeacherLoginController@showLoginForm')->name('dashboard.login.form');

//    Route::get('update_class_id', function (){
//
//        $reports = \App\Report::all();
//        foreach ($reports as $report){
//            $class = \App\Classes::where('id', $report->class_id)->first();
//            \App\Report::where('class_id', $class->id)
//                ->update(['class_id' => $class->class_number]);
//        }
//
//        dd('Done!');
//
//    });

});
