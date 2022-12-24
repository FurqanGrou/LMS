<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/online_welcome', function () {
    return view('welcome.online_welcome');
})->name('welcome.online');

Route::get('/face_to_face_welcome', function () {
    return view('welcome.face_to_face_welcome');
})->name('welcome.face_to_face');

Route::get('/egypt_welcome', function () {
    return view('welcome.egypt_welcome');
})->name('welcome.egypt');

Route::group(['prefix' => 'dashboard_students', 'namespace' => '\App\Http\Controllers\Auth', 'as' => 'students.'], function (){
    Route::get('/login', 'LoginController@showLoginForm')->name('login.form');
    Route::post('/login', 'LoginController@login')->name('login.post');
});

Route::group(['prefix' => 'dashboard_students', 'as' => 'students.', 'middleware' => ['auth']], function (){
//    Auth::routes();
//    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('home', 'StudentController@index')->name('student.index');
    Route::post('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
    Route::get('report/{id}', 'StudentController@showReport')->name('student.showReport');
    Route::get('download/{report_id}', 'StudentController@generate_pdf')->name('student.download');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    echo 'Cache Cleared<br>';
    echo 'Website Optimized';
});


//Route::get('/test-copy-records', function(){
//
//    $all_1_reports = \App\Report::query()->where('created_at', 'like',  '%2022-05-01%')->get();
//    dd($all_1_reports);
//    foreach ($all_1_reports as $report){
//        $report->delete();
//    }
//
//});
