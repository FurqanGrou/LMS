<?php

use App\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if(!function_exists('settings')){
    function settings(){
        return \App\Settings::orderBy('id', 'desc')->first();
    }
}

if(!function_exists('getPeriod')){
    function getPeriod($number){
        $name = '';
        switch ($number){
            case 1: $name = 'الفترة الصباحية';
                break;
            case 2: $name = 'الفترة المسائية الأولى';
                break;
            case 3: $name = 'الفترة المسائية الثانية';
                break;
            case 4: $name = 'الفترة المسائية الثالثة';
                break;
            case 5: $name = 'الفترة المسائية الرابعة';
                break;
        }
        return $name;
    }
}

if(!function_exists('getTitleName')){
    function getTitleName($section){
        return $section == 'female' ? 'ة' : '';
    }
}

if(!function_exists('getMailStatus')){
    function getMailStatus($student_id){

        $tomorrow_date_check = Carbon::tomorrow();
        if(str_contains($tomorrow_date_check->format('l') ,'Friday')){
            $tomorrow_date_check->addDays(2);
        }

        // get the mail status of today
        $tomorrow_report = DB::table('reports')
                ->select('*')
                ->where('student_id', '=', $student_id)
                ->whereDate('created_at', '=', $tomorrow_date_check)
                ->first()->date ?? '404';

        $mail_status = 404;
        if ($tomorrow_report != 404){
            $today = Carbon::now();
            $mail_status = DB::table('reports')
                    ->select('*')
                    ->where('student_id', '=', $student_id)
                    ->whereMonth('created_at', '=', $today->month)
                    ->whereDay('created_at', '=', $today->day)
                    ->whereYear('created_at', '=', $today->year)
                    ->first()->mail_status ?? 404;
        }

        return $mail_status;
    }
}

if(!function_exists('getLessonsNotListenedCount')){
    function getLessonsNotListenedCount($student_id){

        $today = Carbon::tomorrow();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }

        $monthly_report_statistics = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id);

        return $monthly_report_statistics->where(function ($query){
            $query->where('lesson_grade', '=', '0');
            $query->orWhereNull('lesson_grade');
        })->where('absence', '=', 0)->count();
    }
}

if(!function_exists('getLastFivePagesNotListenedCount')){
    function getLastFivePagesNotListenedCount($student_id){

        $today = Carbon::tomorrow();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }
        $monthly_report_statistics = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id);

        return $monthly_report_statistics->where(function ($query){
            $query->where('last_5_pages_grade', '=', '0');
            $query->orWhere('last_5_pages_grade', '=', ' ');
        })->where('absence', '=', 0)->count();
    }
}

if(!function_exists('getDailyRevisionNotListenedCount')){
    function getDailyRevisionNotListenedCount($student_id){

        $today = Carbon::tomorrow();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }

        $monthly_report_statistics = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id);

        return $monthly_report_statistics->where(function ($query){
            $query->where('daily_revision_grade', '=', '0');
            $query->orWhere('daily_revision_grade', '=', ' ');
        })->where('absence', '=', 0)->count();
    }
}

if(!function_exists('getStudentDetails')){
    function getStudentDetails($student_id){
        return \App\User::where('id', '=', $student_id)->first();
    }
}

if(!function_exists('getRate')){
    function getRate($percentage, $lang){

        $message = [];
        switch ($percentage){
            case $percentage >= 90: $message = ['ar' => 'ممتاز', 'en' => 'Excellent'];
                break;
            case $percentage >= 80: $message = ['ar' => 'جيد جداً', 'en' => 'Very Good'];
                break;
            case $percentage >= 70: $message = ['ar' => 'جيد', 'en' => 'Good'];
                break;
            case $percentage >= 50: $message = ['ar' => 'مقبول', 'en' => 'Pass'];
                break;
            case $percentage < 50: $message = ['ar' => 'ضعيف', 'en' => 'Low'];
                break;
        }
        return $message[$lang];
    }
}

function getCurrentDayClass($now = null, $day)
{
    if(is_null($now)){
        $now = Carbon::now();
    }

    $status = str_contains(Carbon::createFromDate($now->year, $now->month, $day)->format('l'), 'Friday') || str_contains(Carbon::createFromDate($now->year, $now->month, $day)->format('l'), 'Saturday') ;
    return $status ? 'custom-bg-gray black' : '';
}

function getTodayMailStatusClass($date, $today, $student_id)
{
    $now = Carbon::now();
    $status = false;
    $class = '';

    if( ($date->year == $now->year) && ($date->month == $now->month) && ($today == $now->day)) {
        $status = getMailStatus($student_id);
        $class = 'bg-warning';
        if($status == 1){
            $class = 'bg-success white';
        }
        if($status == 404){
            $class = 'bg-danger white';
        }
    }


    return $class;
}

function disableRecord($date, $day)
{
    $today    = Carbon::today();
    $tomorrow = Carbon::tomorrow();
    $status = false;

    if(str_contains($tomorrow->format('l') ,'Friday')){
        $tomorrow->addDays(2);
    }

    if(Auth::guard('admin_web')->check()){
        if(
            (($date->year <= $today->year) && ($date->month <= $today->month) && ($day <= $today->day))
            || (($date->year == $tomorrow->year) && ($date->month == $tomorrow->month) && ($day == $tomorrow->day))
        ) {
            $status = true;
        }
    }

    if(Auth::guard('teacher_web')->check()){

        $teacher_email = auth()->user()->email;
        $class_number  = \App\User::query()->find(request()->student_id)->class_number;
        $role = \App\ClassesTeachers::query()
                ->where('teacher_email', '=', $teacher_email)
                ->where('class_number', '=', $class_number)
                ->first()->role ?? '';

        if($role == 'supervisor'){
            if(
                (($date->year == $today->year) && ($date->month == $today->month) && ($day <= $today->day))
                || (($date->year == $tomorrow->year) && ($date->month == $tomorrow->month) && ($day == $tomorrow->day))
            ){
                $status = true;
            }
        }else{
            if(
                (($date->year == $today->year) && ($date->month == $today->month) && ($day == $today->day))
                || (($date->year == $tomorrow->year) && ($date->month == $tomorrow->month) && ($day == $tomorrow->day))
            ){
                $status = true;
            }
        }
    }

    return $status ? '' : 'disabled';
//    return $class_number;
}

function disableRecordGrade($date, $day)
{
    $today    = Carbon::today();
    $tomorrow = Carbon::tomorrow();
    $status = true;

    if(str_contains($tomorrow->format('l') ,'Friday')){
        $tomorrow->addDays(2);
    }

    if(
    (($date->year == $tomorrow->year) && ($date->month == $tomorrow->month) && ($day == $tomorrow->day))
    ){
        $status = false;
    }

    return $status ? '' : 'disabled';
}

function getReportLesson($report_id)
{
    return Report::query()->where('id', '=', $report_id)->first()->new_lesson ?? '';
}

function dateFormatMail($now, $day)
{
    return \Carbon\Carbon::createFromDate($now->year, $now->month, $day)->format('l d-m-Y');
}
