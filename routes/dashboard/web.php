<?php

use App\Exports\ExamRequestsExport;
use App\Exports\MonthlyScoresExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use GuzzleHttp\Client;

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

Route::group(['prefix' => 'dashboard-admins', 'namespace' => '\App\Http\Controllers\Auth', 'as' => 'admins.'], function (){
    Route::get('/login', 'AdminLoginController@showLoginForm')->name('login.form');
    Route::post('/login', 'AdminLoginController@login')->name('login.post');
});

Route::group(['prefix' => 'dashboard-admins', 'middleware' => ['auth:admin_web'], 'as' => 'admins.'], function (){

    // logout
    Route::post('/logout', '\App\Http\Controllers\Auth\AdminLoginController@logout')->name('logout');

    // home
    Route::get('/', 'HomeController@index')->name('home');
    // monthly scores export
    Route::get('monthly-scores-export', 'ImportExportController@exportMonthlyScoresIndex')->name('monthly_scores.index');
    Route::post('monthly-scores-export', 'ImportExportController@exportMonthlyScores')->name('monthly_scores.export');

    // admins
    Route::get('/admins', 'AdminController@index')->name('admins.index');
    Route::get('/admins/{admin}/edit', 'AdminController@edit')->name('admins.edit');
    Route::put('/admins/{admin}/update', 'AdminController@update')->name('admins.update');
    Route::delete('/admins/destroy/all', 'AdminController@deleteAll')->name('admins.deleteAll');
    Route::delete('/admins/destroy/{id}', 'AdminController@destroy')->name('admins.destroy');
    Route::get('/admins/create', 'AdminController@create')->name('admins.create');
    Route::post('/admins/store', 'AdminController@store')->name('admins.store');

    Route::get('/admins/audits', 'AuditController@index')->name('audits.index');

    // import students
    Route::get('importStudents', 'ImportExportController@importStudentsView')->name('import.students.view');
    Route::post('importStudents', 'ImportExportController@importStudents')->name('import.students.store');

    // import lessons and chapters
    Route::get('importLessons', 'ImportExportController@importLessons')->name('import.lessons');
    Route::get('importChapters', 'ImportExportController@importChapters')->name('import.chapters');
    Route::get('importParts', 'ImportExportController@importParts')->name('import.parts');

    // import classes
//    Route::get('importClasses', 'ImportExportController@importClassesView')->name('import.classes.view');
//    Route::post('importClasses', 'ImportExportController@importClasses')->name('import.classes.store');

    // import teachers
//    Route::get('importTeachers', 'ImportExportController@importTeachersView')->name('import.teachers.view');
//    Route::post('importTeachers', 'ImportExportController@importTeachers')->name('import.teachers.store');

    // import classes_teachers
//    Route::get('importClassesTeachers', 'ImportExportController@importClassesTeachersView')->name('import.classes_teachers.view');
//    Route::post('importClassesTeachers', 'ImportExportController@importClassesTeachers')->name('import.classes_teachers.store');
//    Route::get('export', 'ImportExportController@export')->name('export');

    // classes
    Route::get('classes', 'ClassesController@index')->name('classes.index');
    Route::get('/class_students/{class_number}', 'ClassesController@classStudents')->name('classes.students');
    Route::get('/join_requests', 'ClassesController@joinRequests')->name('classes.join_requests');
    Route::get('/respond_request', 'ClassesController@respondRequest')->name('classes.respond_request');

    // students
    Route::get('students', 'UserController@index')->name('student.index');

    // reports
    Route::get('reports', 'ReportController@exportIndex')->name('report.index');
    Route::post('reports', 'ReportController@exportStore')->name('report.export');

    // disable teachers login
    Route::get('disable_teachers_login', 'AdminController@disableTeachersLogin')->name('disable.teachers.login');

    Route::get('absences', 'AbsenceController@index')->name('absences.index');
    Route::get('absence_type', 'AbsenceController@absenceType')->name('absence.type');
    Route::post('absences', 'AbsenceController@export')->name('absence.export');

    Route::get('/report/table/{student_id}', 'ReportController@reportTable')->name('report.table');
    Route::post('/report/table/{student_id}', 'ReportController@reportTableStore')->name('report.table');
    Route::post('/report/send/{student_id}', 'ReportController@sendReportTable')->name('send.report');
    Route::post('/report/change_page_number/{student_id}', 'ReportController@changePageNumber')->name('report.changePageNumber');
    Route::post('/report/send-monthly/{student_id}', 'ReportController@sendReportTableMonthly')->name('send.report.monthly');
    Route::post('/report/update_monthly_scores_event', 'ReportController@fireUpdateMonthlyScoresEvent')->name('report.updateMonthlyScoresEvent');

    // attendance
    Route::get('/attendance', 'AttendanceController@create')->name('attendance.index');
    Route::post('/attendance', 'AttendanceController@store')->name('attendance.store');

    // attendance export
    Route::get('attendance-export', 'AttendanceController@exportIndex')->name('attendance.export_index');
    Route::post('attendance-export', 'AttendanceController@export')->name('attendance.export');

    // request services
//    Route::get('/request-services', 'RequestServiceController@index')->name('request_services.index');
//    Route::get('/request-services/{service}', 'RequestServiceController@show')->name('request_services.show');
//    Route::put('/request-services/{service}/update', 'RequestServiceController@update')->name('request_services.update');
    Route::get('request-services/export', 'ImportExportController@exportExamsRequests')->name('request_services.exams.export');

    Route::get('change-send-monthly-report-status', 'AdminController@changeSendMonthlyReportStatus')->name('change_send_monthly_report_status.index');
    Route::put('change-send-monthly-report-status', 'AdminController@changeSendMonthlyReportStatusUpdate')->name('change_send_monthly_report_status.update');

});

Route::get('clear-cache', function (){
    clearCache();
    echo "Clear Done";
});

Route::get('test-code', function (){

});

