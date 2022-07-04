<?php

use App\AttendanceAbsenceRequests;
use App\ClassesTeachers;
use App\MonthlyScore;
use App\Report;
use App\Teacher;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
    Route::get('/online/login', 'TeacherLoginController@showLoginFormOnline')->name('login.form.online');
    Route::get('/face_to_face/login', 'TeacherLoginController@showLoginFormFaceToFace')->name('login.form.face_to_face');

    Route::post('/login-post', 'TeacherLoginController@login')->name('login.post');
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
    Route::post('/report/update_monthly_scores_event', 'ReportController@fireUpdateMonthlyScoresEvent')->name('report.updateMonthlyScoresEvent');

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

    Route::get('/modification-request/create', 'ModificationRequestController@create')->name('modification_request.create');
    Route::post('/modification-request', 'ModificationRequestController@store')->name('modification_request.store');

    Route::get('/request-services/meetings/create', 'RequestServiceController@createMeeting')->name('request_services.meetings.create');
    Route::post('/request-services/meetings', 'RequestServiceController@storeMeeting')->name('request_services.meetings.store');

    Route::get('/request-services/meetings-admin/create', 'RequestServiceController@createMeetingWithAdmin')->name('request_services.meetings-admin.create');
    Route::post('/request-services/meetings-admin', 'RequestServiceController@storeMeetingWithAdmin')->name('request_services.meetings.store');

    //Attendance Absence
    Route::get('/request-services/attendanceAbsence', 'RequestServiceController@attendanceAbsenceTeachers')->name('request_services.attendanceAbsenceTeachers.create');
    Route::post('/request-services/attendanceAbsence', 'RequestServiceController@attendanceAbsenceTeachersStore')->name('request_services.attendanceAbsenceTeachers.store');
    Route::get('/request-services/all-attendanceAbsence/', 'RequestServiceController@showAppliedRequests')->name('request_services.attendanceAbsenceTeachers.index');
    Route::get('/request-services/attendanceAbsence/{attendanceAbsenceRequests}', 'RequestServiceController@showSingleAppliedRequest')->name('request_services.attendanceAbsenceTeachers.show');
    Route::get('/request-services/attendanceAbsence/{attendanceAbsenceRequests}/edit', 'RequestServiceController@editAttendanceAbsence')->name('request_services.attendanceAbsenceTeachers.edit');
    Route::put('/request-services/attendanceAbsence/{attendanceAbsenceRequests}', 'RequestServiceController@updateAttendanceAbsence')->name('request_services.attendanceAbsenceTeachers.update');

    // ajax to check period of class
    Route::post('/request-services/attendanceAbsence/checkPeriod', 'RequestServiceController@checkPeriod')->name('request_services.attendanceAbsenceTeachers.checkPeriod');

    //ajax to cancel requests
    Route::post('/request-services/cancel-request/{attendanceAbsenceRequests}', 'RequestServiceController@cancelRequest')->name('request_services.cancel-request');

});
