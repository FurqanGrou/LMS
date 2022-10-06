<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.'], function (){
    Route::get('/get-lessons', 'StudentPlan@lessons')->name('lessons');
    Route::get('/get-ayat', 'StudentPlan@ayat')->name('ayat');
    Route::post('/save-student-plan', 'StudentPlan@update')->name('student.plan');
});
