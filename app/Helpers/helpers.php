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

function getTitleName($section){
    return $section == 'female' ? 'ة' : '';
}

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

function getAbsenceCount($student_id){

    $today = Carbon::tomorrow();
    $currentMonth = date('m');
    $currentYear = date('Y');

    if(request()->date_filter) {
        $currentMonth = substr(request()->date_filter, -2);
        $currentYear = substr(request()->date_filter, 0, 4);
    }

    $absence_times = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id)
        ->where('absence', '!=', 0)
        ->count();

//    return $absence_times;
        if(checkThirdCondition($student_id)){
            $sat_frid = Report::query()
                ->whereRaw('YEAR(created_at) = ?', [$currentYear])
                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                ->whereDate('created_at', '<', $today)
                ->where(function ($query){
                    $query->where('date', 'like', '%Saturday%');
                    $query->orWhere('date', 'like', '%Friday%');
                })
                ->where('student_id', '=', $student_id)
                ->where('absence', '=', '0')
                ->count();

            return max($absence_times - $sat_frid, 0);
        }

    return $absence_times;
}

function checkThirdCondition($student_id){

    $today = Carbon::tomorrow();
    $currentMonth = date('m');
    $currentYear = date('Y');

    if(request()->date_filter) {
        $currentMonth = substr(request()->date_filter, -2);
        $currentYear = substr(request()->date_filter, 0, 4);
    }

    $absence_times = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id)
        ->where('absence', '!=', 0)
        ->count();

    // by default is ok, all grades completed
    $incomplete_default_grades_count = 0;
    $status = false;

    if($absence_times > 0){
        $incomplete_default_grades_count = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where(function ($query){
                $query->where('lesson_grade', '<', 1);
                $query->orWhere('last_5_pages_grade', '<', 1);
                $query->orWhere('daily_revision_grade', '<', 2);
                $query->orWhere('behavior_grade', '<', 1);
            })
            ->where('absence', '=', '0')
            ->count();
    }

    if($incomplete_default_grades_count == 0 && $absence_times > 0){
        $status = true;
    }

    return $status;
}

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

    $clonedQuery = clone $monthly_report_statistics;

    $normal_count = $monthly_report_statistics->where(function ($query){
                        $query->where('lesson_grade', '=', '0');
                        $query->orWhereNull('lesson_grade');
                    })->where('absence', '=', 0)
                      ->count();

    $clonedQuery = $clonedQuery->where('lesson_grade', '>', 1)
                    ->where('last_5_pages_grade', '>=', 1)
                    ->where('daily_revision_grade', '>=', 2)
                    ->where('behavior_grade', '>=', 1);

    $over_count = clone $clonedQuery;
    $over_count = $over_count->count();
    $over_count_total = $clonedQuery->sum('lesson_grade');

    // Third condition from PDF of absence rules
    // 1- have absence
    // 2- incomplete default grades count

    if(checkThirdCondition($student_id)){

        $absence_times = Report::query()
                                ->whereRaw('YEAR(created_at) = ?', [$currentYear])
                                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                                ->whereDate('created_at', '<', $today)
                                ->where('date', 'not like', '%Saturday%')
                                ->where('date', 'not like', '%Friday%')
                                ->where('student_id', '=', $student_id)
                                ->where('absence', '!=', 0)
                                ->count();

        // number of over lesson grade
        $over_count = Report::query()
                            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
                            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                            ->whereDate('created_at', '<', $today)
                            ->where(function ($query){
                                $query->where('date', 'like', '%Friday%');
                                $query->orWhere('date', 'like', '%Saturday%');
                            })
                            ->where('student_id', '=', $student_id)
                            ->orderBy('created_at', 'desc')
                            ->take($absence_times)
                            ->count();

        // total of lesson grades in Saturday and Friday
        $over_count_total = Report::query()
                                ->whereRaw('YEAR(created_at) = ?', [$currentYear])
                                ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                                ->whereDate('created_at', '<', $today)
                                ->where(function ($query){
                                    $query->where('date', 'like', '%Friday%');
                                    $query->orWhere('date', 'like', '%Saturday%');
                                })
                                ->where('student_id', '=', $student_id)
                                ->orderBy('created_at', 'desc')
                                ->take($absence_times)
                                ->sum('lesson_grade');

        return max($normal_count - ($over_count_total - (1 * $over_count)), 0) ;
    }

    // يومي الجمعة والسبت لازم يكون درجة الدرس الجديد أكبر من صفر فقط ولا يشترط أن تكون أكبر من الافتراضي
    $over_count_total_sat = Report::query()
                        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
                        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
                        ->whereDate('created_at', '<', $today)
                        ->where(function ($query){
                            $query->where('date', 'like', '%Friday%');
                            $query->orWhere('date', 'like', '%Saturday%');
                        })
                        ->where('student_id', '=', $student_id)
                        ->where('lesson_grade', '>', 0)
                        ->sum('lesson_grade');

    // يومي الجمعة والسبت لا يشترط ان تكون جميع الدرجات فيه مكتملة انما كل درجة تقابلها تعوبض درجة من يوم أخر

    return max($normal_count - ( ($over_count_total - (1 * $over_count)) + $over_count_total_sat ), 0) ;
}

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

    $clonedQuery = clone $monthly_report_statistics;

    $normal_count = $monthly_report_statistics->where(function ($query){
                                $query->where('last_5_pages_grade', '=', '0');
                                $query->orWhere('last_5_pages_grade', '=', ' ');
                            })->where('absence', '=', 0)->count();

    $clonedQuery = $clonedQuery->where('lesson_grade', '>', 1)
                                ->where('last_5_pages_grade', '>=', 1)
                                ->where('daily_revision_grade', '>=', 2)
                                ->where('behavior_grade', '>=', 1);

    $over_count = clone $clonedQuery;
    $over_count = $over_count->count();
    $over_count_total = $clonedQuery->sum('last_5_pages_grade');

    // Third condition from PDF of absence rules
    // 1- have absence
    // 2- incomplete default grades count

    if(checkThirdCondition($student_id)){

        $absence_times = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where('absence', '!=', 0)
            ->count();

        // number of over lesson grade
        $over_count = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->count();

        // total of lesson grades in Saturday and Friday
        $over_count_total = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->sum('last_5_pages_grade');

        return max($normal_count - ($over_count_total - (1 * $over_count)), 0) ;
    }

    // يومي الجمعة والسبت لازم يكون درجة الدرس الجديد أكبر من صفر فقط ولا يشترط أن تكون أكبر من الافتراضي
    $over_count_total_sat = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<', $today)
        ->where(function ($query){
            $query->where('date', 'like', '%Friday%');
            $query->orWhere('date', 'like', '%Saturday%');
        })
        ->where('student_id', '=', $student_id)
        ->where('last_5_pages_grade', '>', 0)
        ->sum('last_5_pages_grade');

    // يومي الجمعة والسبت لا يشترط ان تكون جميع الدرجات فيه مكتملة انما كل درجة تقابلها تعوبض درجة من يوم أخر

    return max($normal_count - ( ($over_count_total - (1 * $over_count)) + $over_count_total_sat ), 0) ;
}

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

    $clonedQuery = clone $monthly_report_statistics;

    $normal_count = $monthly_report_statistics->where(function ($query){
                                        $query->where('daily_revision_grade', '=', '0');
                                        $query->orWhere('daily_revision_grade', '=', ' ');
                                        $query->orWhereNull('daily_revision_grade');
                                    })->where('absence', '=', 0)->count();

    $clonedQuery = $clonedQuery->where('lesson_grade', '>', 1)
                                ->where('last_5_pages_grade', '>=', 1)
                                ->where('daily_revision_grade', '>=', 2)
                                ->where('behavior_grade', '>=', 1);

    $over_count = clone $clonedQuery;
    $over_count = $over_count->count();
    $over_count_total = $clonedQuery->sum('daily_revision_grade');

    // Third condition from PDF of absence rules
    // 1- have absence
    // 2- incomplete default grades count

    if(checkThirdCondition($student_id)){

        $absence_times = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where('absence', '!=', 0)
            ->count();

        // number of over lesson grade
        $over_count = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->count();

        // total of lesson grades in Saturday and Friday
        $over_count_total = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->sum('daily_revision_grade');

        return max($normal_count - ($over_count_total - (2 * $over_count)), 0) ;
    }

    // يومي الجمعة والسبت لازم يكون درجة الدرس الجديد أكبر من صفر فقط ولا يشترط أن تكون أكبر من الافتراضي
    $over_count_total_sat = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<', $today)
        ->where(function ($query){
            $query->where('date', 'like', '%Friday%');
            $query->orWhere('date', 'like', '%Saturday%');
        })
        ->where('student_id', '=', $student_id)
        ->where('daily_revision_grade', '>', 0)
        ->sum('daily_revision_grade');

    // يومي الجمعة والسبت لا يشترط ان تكون جميع الدرجات فيه مكتملة انما كل درجة تقابلها تعوبض درجة من يوم أخر

    return max($normal_count - ( ($over_count_total - (2 * $over_count)) + $over_count_total_sat ), 0) ;
}

function getStudentDetails($student_id){
    return \App\User::where('id', '=', $student_id)->first();
}

function getRate($percentage, $lang){

    $message = [];
    if($percentage >= 90){
        $message = ['ar' => 'ممتاز', 'en' => 'Excellent'];
    }elseif ($percentage >= 80){
        $message = ['ar' => 'جيد جداً', 'en' => 'Very Good'];
    }elseif ($percentage >= 70){
        $message = ['ar' => 'جيد', 'en' => 'Good'];
    }elseif ($percentage >= 50){
        $message = $message = ['ar' => 'مقبول', 'en' => 'Pass'];
    }elseif ($percentage < 50){
        $message = $message = ['ar' => 'ضعيف', 'en' => 'Low'];
    }
    return $message[$lang];
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

    if(str_contains($tomorrow->format('l') ,'Saturday')){
        $tomorrow->addDays(1);
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
}

function disableRecordGrade($date, $day)
{
    $today    = Carbon::today();
    $tomorrow = Carbon::tomorrow();
    $status = true;

    if(str_contains($tomorrow->format('l') ,'Friday')){
        $tomorrow->addDays(2);
    }

    if(str_contains($tomorrow->format('l') ,'Saturday')){
        $tomorrow->addDays(1);
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
