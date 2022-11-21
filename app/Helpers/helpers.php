<?php

use App\DropoutStudent;
use App\Report;
use App\TopTrackerEmployee;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

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

function getAbsenceCount($student_id, $type, $month = false){

//    $path = getStudentPath($student_id); // تلاوة

    if($month){
        $currentMonth = substr($month, -2);
        $currentYear  = substr($month, 0, 4);
        $myDate = "$currentMonth/01/$currentYear";
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->lastOfMonth();

//        if (!empty(getStudentPath($student_id, $month))){
//            $student_path = getStudentPath($student_id, $month);
//        }
    }else{
        $today = getAppropriateToday();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }
    }

    $absence_times = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id)
        ->where('absence', '=', $type)
        ->count();

    // if($path == "قسم التلاوة"){

    //     $unexcused_days = Report::query()
    //         ->whereRaw('YEAR(created_at) = ?', [$currentYear])
    //         ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
    //         ->whereDate('created_at', '<=', $today)
    //         ->where('date', 'not like', '%Saturday%')
    //         ->where('date', 'not like', '%Friday%')
    //         ->where('student_id', '=', $student_id)
    //         ->where('absence', '=', -5)
    //         ->count();

    //     $excuse_days = Report::query()
    //         ->whereRaw('YEAR(created_at) = ?', [$currentYear])
    //         ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
    //         ->whereDate('created_at', '<=', $today)
    //         ->where('date', 'not like', '%Saturday%')
    //         ->where('date', 'not like', '%Friday%')
    //         ->where('student_id', '=', $student_id)
    //         ->where('absence', '=', -2)
    //         ->count(); // 0

    //     $compensation_days = Report::query()
    //         ->whereRaw('YEAR(created_at) = ?', [$currentYear])
    //         ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
    //         ->whereDate('created_at', '<=', $today)
    //         ->where(function ($query){
    //             $query->where('date', 'like', '%Friday%');
    //             $query->orWhere('date', 'like', '%Saturday%');
    //         })->where('student_id', '=', $student_id)
    //         ->count();

    //     $unexcused_absence = max($unexcused_days-$compensation_days-8, 0);
    //     $remainderOf8 = min($unexcused_days-$compensation_days-8, 0)*-1;
    //     $excuse_absence = max($excuse_days - $remainderOf8, 0);

    //     if ($type == -2){
    //         return $excuse_absence;
    //     }

    //     if ($type = -5){
    //         return $unexcused_absence;
    //     }
    // }

    // معندوش اي نقص باي يوم عن الدرجات الافتراضية True
    // يكون عنده ايام غياب True
    if (isAchievedDefaultGrades($student_id, $month) && $absence_times){
        // يتم التعويض من خلال ايام الجمعة والسبت بدل ايام الغياب بعذر
        // ومن ثم الغياب بدون عذر بحيث تكون الاولوية للغياب بعذر في التعويض

        // هنجيب عدد ايام الحضور التعويضي
        $compensation_days = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })->where('student_id', '=', $student_id)
            ->count();  // 2

        // لازم نجيب عدد ايام الغياب بعذر
        $excuse_days = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where('absence', '=', -2)
            ->count(); // 0

        $unexcused_days = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where('absence', '=', -5)
            ->count(); // 9

        $remainder_compensation_days = max($compensation_days - $unexcused_days, 0); // 3 - 9 = 0
        // لو كان عدد ايام الحضور التعويضي اقل من او يساوي الغياب بعذر
        // ننتهي هنا ولا نتوجه للتعويض في البدون عذر

        if($type == -2){
            return max($excuse_days - $remainder_compensation_days, 0); // 2
        }

        if($type == -5) {
            // غير كدا هنروح نجيب ما تبقي من عدد ايام الحضور التعويضي ونطرحه من عدد ايام الغياب بدون عذر
            // الناتج النهائي من هذه المعادالات نستخدمه في السطر التالي

            // هنروح نجيب عدد ايام الحضور في السبت والجمعة ونعمللها طرح - من عدد ايام الغياب
            return max($unexcused_days - $compensation_days, 0); // 9 - 3 = 6
        }
    }

    return $absence_times;
}

function getStudentPath($student_id, $month_year = null){

    $path = Cache::remember('student_path.' . $student_id, 60 * 60 * 24, function() use ($student_id) {
        return User::find($student_id)->path;
    });

    $current_month_year = Carbon::today()->format('Y-m');

    if ($current_month_year != $month_year && !is_null($month_year)){
        $previous_path = \App\MonthlyScore::query()->where('month_year', '=', $month_year)->where('user_id', '=', $student_id)->first()->path ?? null;
        if (!is_null($previous_path)){
            $path = $previous_path;
        }
    }

    return $path;
}

function getPathDefaultGrade($path, $grade){

    $grades = [
        'قسم الحفظ' => [
            'new_lesson' => 1,
            'last_5_pages' => 1,
            'daily_revision' => 2,
            'behavior' => 1
        ],
        'قسم الهجاء' => [
            'new_lesson' => 1,
            'last_5_pages' => 1,
            'daily_revision' => 2,
            'behavior' => 1
        ],
        'قسم التأسيس' => [
            'new_lesson' => 1,
            'last_5_pages' => 1,
            'daily_revision' => 2,
            'behavior' => 1
        ],
        'قسم التلاوة' => [
            'new_lesson' => 2,
            'last_5_pages' => 1,
            'daily_revision' => 1,
            'behavior' => 1
        ],
        'تمكين' => [
            'new_lesson' => 3,
            'last_5_pages' => 1,
            'daily_revision' => 1,
            'behavior' => 1
        ],
    ];

    return $grades[$path][$grade];
}

function isAchievedDefaultGrades($student_id, $month = false){

    $student_path = getStudentPath($student_id);

    if($month){
        $currentMonth = substr($month, -2);
        $currentYear  = substr($month, 0, 4);
        $myDate = "$currentMonth/01/$currentYear";
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->lastOfMonth();

        if (!empty(getStudentPath($student_id, $month))){
            $student_path = getStudentPath($student_id, $month);
        }
    }else{
        $today = getAppropriateToday();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }
    }

    $default_new_lesson_grade = getPathDefaultGrade($student_path, 'new_lesson');
    $default_daily_revision_grade = getPathDefaultGrade($student_path, 'daily_revision');
    $default_last_5_pages_grade = getPathDefaultGrade($student_path, 'last_5_pages');
    $default_behavior_grade = getPathDefaultGrade($student_path, 'behavior');

    $result = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id)
        ->where(function ($query) use ($default_new_lesson_grade, $default_last_5_pages_grade, $default_daily_revision_grade, $default_behavior_grade){
            $query->where('lesson_grade', '<', $default_new_lesson_grade);
            $query->orWhere('last_5_pages_grade', '<', $default_last_5_pages_grade);
            $query->orWhere('daily_revision_grade', '<', $default_daily_revision_grade);
            $query->orWhere('behavior_grade', '<', $default_behavior_grade);
        })
        ->where('absence', '=', '0')
        ->count();

    return !($result > 0);
}

function checkThirdCondition($student_id, $month = false){

    if($month){
        $currentMonth = substr($month, -2);
        $currentYear  = substr($month, 0, 4);
        $myDate = "$currentMonth/01/$currentYear";
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->lastOfMonth();
    }else{
        $today = getAppropriateToday();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }
    }

    $absence_times = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id)
        ->where('absence', '!=', '0')
        ->where('absence', '!=', '-1')
        ->count();

    return (isAchievedDefaultGrades($student_id, $month) && $absence_times > 0);
}

function getLessonsNotListenedCount($student_id, $month = false){

    $student_path = getStudentPath($student_id);

    if($month){
        $currentMonth = substr($month, -2);
        $currentYear  = substr($month, 0, 4);
        $myDate = "$currentMonth/01/$currentYear";
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->lastOfMonth();

        if (!empty(getStudentPath($student_id, $month))){
            $student_path = getStudentPath($student_id, $month);
        }
    }else{
        $today = getAppropriateToday(); // tomorrow
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter){
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear  = substr(request()->date_filter, 0, 4);
        }
    }

    $default_new_lesson_grade = getPathDefaultGrade($student_path, 'new_lesson'); // 2
    $default_daily_revision_grade = getPathDefaultGrade($student_path, 'daily_revision');
    $default_last_5_pages_grade = getPathDefaultGrade($student_path, 'last_5_pages');
    $default_behavior_grade = getPathDefaultGrade($student_path, 'behavior');

    $monthly_report_statistics = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id);

    $clonedQuery = clone $monthly_report_statistics;

    if ($student_path != 'قسم التلاوة'){
        $normal_count = $monthly_report_statistics->where(function ($query) use ($default_new_lesson_grade){
            $query->where('lesson_grade', '=', '0');
            $query->orWhere('lesson_grade', '=', ' ');
            $query->orWhereNull('lesson_grade');
            $query->orWhere('lesson_grade', '<', $default_new_lesson_grade);
        })->where('absence', '=', 0)
            ->count();
    }else{
        $normal_count = $monthly_report_statistics->where(function ($query){
            $query->where('lesson_grade', '=', '0');
            $query->orWhere('lesson_grade', '=', ' ');
            $query->orWhereNull('lesson_grade');
        })->where('absence', '=', 0)
            ->count();
    }

    $clonedQuery = $clonedQuery->where('lesson_grade', '>', $default_new_lesson_grade)
        ->where('last_5_pages_grade', '>=', $default_last_5_pages_grade)
        ->where('daily_revision_grade', '>=', $default_daily_revision_grade)
        ->where('behavior_grade', '>=', $default_behavior_grade);

    $over_count = clone $clonedQuery;
    $over_count = $over_count->count();
    $over_count_total = $clonedQuery->sum('lesson_grade');

    // Third condition from PDF of absence rules
    // 1- have absence
    // 2- incomplete default grades count

    if(checkThirdCondition($student_id, $month)){

        $absence_times = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where(function ($query){
                $query->where('absence', '=', -5);
                $query->orWhere('absence', '=', -2);
            })->count();

        // number of over lesson grade
        $over_count = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->count();

        // total of lesson grades in Saturday and Friday
        $over_count_total = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->sum('lesson_grade');

        return max($normal_count - ($over_count_total - ($default_new_lesson_grade * $over_count)), 0) ;
    }

    // يومي الجمعة والسبت لازم يكون درجة الدرس الجديد أكبر من صفر فقط ولا يشترط أن تكون أكبر من الافتراضي
    $over_count_total_sat = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where(function ($query){
            $query->where('date', 'like', '%Friday%');
            $query->orWhere('date', 'like', '%Saturday%');
        })
        ->where('student_id', '=', $student_id)
        ->where('lesson_grade', '>', 0)
        ->sum('lesson_grade');

    // يومي الجمعة والسبت لا يشترط ان تكون جميع الدرجات فيه مكتملة انما كل درجة تقابلها تعوبض درجة من يوم أخر

    return max($normal_count - ( ( ($over_count_total - ($default_new_lesson_grade * $over_count)) / $default_new_lesson_grade ) + ($over_count_total_sat/$default_new_lesson_grade) ), 0);
    //          9            - (  ( ( 0               -  ( 2                        * 0         )) /  2                         ) + 0                    )      = 6
}

function getLastFivePagesNotListenedCount($student_id, $month = false){

    $student_path = getStudentPath($student_id);
    if($month){
        $currentMonth = substr($month, -2);
        $currentYear  = substr($month, 0, 4);
        $myDate = "$currentMonth/01/$currentYear";
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->lastOfMonth();

        if (!empty(getStudentPath($student_id, $month))){
            $student_path = getStudentPath($student_id, $month);
        }
    }else{
        $today = getAppropriateToday(); // tomorrow
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }
    }

    $default_new_lesson_grade = getPathDefaultGrade($student_path, 'new_lesson');
    $default_daily_revision_grade = getPathDefaultGrade($student_path, 'daily_revision');
    $default_last_5_pages_grade = getPathDefaultGrade($student_path, 'last_5_pages');
    $default_behavior_grade = getPathDefaultGrade($student_path, 'behavior');

    $monthly_report_statistics = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id);

    $clonedQuery = clone $monthly_report_statistics;

    $normal_count = $monthly_report_statistics->where(function ($query){
        $query->where('last_5_pages_grade', '=', '0');
        $query->orWhere('last_5_pages_grade', '=', ' ');
        $query->orWhereNull('last_5_pages_grade');
    })->where('absence', '=', 0)
        ->count();

    $clonedQuery = $clonedQuery->where('lesson_grade', '>=', $default_new_lesson_grade)
        ->where('last_5_pages_grade', '>', $default_last_5_pages_grade)
        ->where('daily_revision_grade', '>=', $default_daily_revision_grade)
        ->where('behavior_grade', '>=', $default_behavior_grade);

    $over_count = clone $clonedQuery;
    $over_count = $over_count->count();
    $over_count_total = $clonedQuery->sum('last_5_pages_grade');

    // Third condition from PDF of absence rules
    // 1- have absence
    // 2- incomplete default grades count

    if(checkThirdCondition($student_id, $month)){

        $absence_times = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where('absence', '!=', 0)
            ->where('absence', '!=', '-1')
            ->count();

        // number of over lesson grade
        $over_count = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
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
            ->whereDate('created_at', '<=', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->sum('last_5_pages_grade');

        return max($normal_count - ($over_count_total - ($default_last_5_pages_grade * $over_count)), 0);
    }

    // يومي الجمعة والسبت لازم يكون درجة الدرس الجديد أكبر من صفر فقط ولا يشترط أن تكون أكبر من الافتراضي
    $over_count_total_sat = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where(function ($query){
            $query->where('date', 'like', '%Friday%');
            $query->orWhere('date', 'like', '%Saturday%');
        })
        ->where('student_id', '=', $student_id)
        ->where('last_5_pages_grade', '>', 0)
        ->sum('last_5_pages_grade');

    // يومي الجمعة والسبت لا يشترط ان تكون جميع الدرجات فيه مكتملة انما كل درجة تقابلها تعوبض درجة من يوم أخر

    // return max($normal_count - ( ($over_count_total - (1 * $over_count)) + $over_count_total_sat ), 0) ;

    return max($normal_count - ( ( ($over_count_total - ($default_last_5_pages_grade * $over_count)) / $default_last_5_pages_grade ) + ($over_count_total_sat/$default_last_5_pages_grade) ), 0);
    //          9            - (  ( ( 0               -  ( 2                        * 0         )) /  2                         ) + 0                    )      = 6
}

function getDailyRevisionNotListenedCount($student_id, $month = false){

    $student_path = getStudentPath($student_id);

    if($month){
        $currentMonth = substr($month, -2);
        $currentYear  = substr($month, 0, 4);
        $myDate = "$currentMonth/01/$currentYear";
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->lastOfMonth();

        if (!empty(getStudentPath($student_id, $month))){
            $student_path = getStudentPath($student_id, $month);
        }
    }else{
        $today = getAppropriateToday(); // tomorrow
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
        }
    }

    $default_new_lesson_grade = getPathDefaultGrade($student_path, 'new_lesson');
    $default_daily_revision_grade = getPathDefaultGrade($student_path, 'daily_revision');
    $default_last_5_pages_grade = getPathDefaultGrade($student_path, 'last_5_pages');
    $default_behavior_grade = getPathDefaultGrade($student_path, 'behavior');

    // = count + summation

    $monthly_report_statistics = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where('date', 'not like', '%Saturday%')
        ->where('date', 'not like', '%Friday%')
        ->where('student_id', '=', $student_id);

    $clonedQuery = clone $monthly_report_statistics;
    $clonedSummationQuery = clone $monthly_report_statistics;

    // get count of records that daily_revision_grade column is zero, empty or null
    $normal_count = $monthly_report_statistics->where(function ($query){
        $query->where('daily_revision_grade', '=', '0');
        $query->orWhere('daily_revision_grade', '=', ' ');
        $query->orWhereNull('daily_revision_grade');
    })->where('absence', '=', 0)
        ->count();

    // get count of records that daily_revision_grade column is 0.5
//    $half_grade_count = $monthly_report_statistics->where(function ($query){
//                                                    $query->where('daily_revision_grade', '=', '0.5');
//                                                })->where('absence', '=', 0)
//                                                ->count();
//    $result = ($half_grade_count/2) + $normal_count;

    // get summation of grades less than default and greater than zero in daily_revision_grade column
    $summation = $clonedSummationQuery->where('daily_revision_grade', '>', '0')
            ->where('daily_revision_grade', '<', $default_daily_revision_grade)
            ->where('absence', '=', '0')
            ->sum('daily_revision_grade')/$default_daily_revision_grade;

    $normal_count +=$summation;

    $clonedQuery = $clonedQuery->where('lesson_grade', '>=', $default_new_lesson_grade)
        ->where('last_5_pages_grade', '>=', $default_last_5_pages_grade)
        ->where('daily_revision_grade', '>', $default_daily_revision_grade)
        ->where('behavior_grade', '>=', $default_behavior_grade);

    $over_count = clone $clonedQuery;
    $over_count = $over_count->count();
    $over_count_total = $clonedQuery->sum('daily_revision_grade');

    // Third condition from PDF of absence rules
    // 1- have absence
    // 2- incomplete default grades count

    if(checkThirdCondition($student_id, $month)){

        // عدد مرات الغياب الطبيعي الاجمالي
        $absence_times = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where('date', 'not like', '%Saturday%')
            ->where('date', 'not like', '%Friday%')
            ->where('student_id', '=', $student_id)
            ->where('absence', '!=', 0)
            ->where('absence', '!=', '-1')
            ->count();

        // عدد مرات الحضور في السبت والجمعة
        // number of over lesson grade
        $over_count = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->count();

        // اجمالي مجموع الدرجات في ايام الجمعة والسبت
        // total of lesson grades in Saturday and Friday
        $over_count_total = Report::query()
            ->whereRaw('YEAR(created_at) = ?', [$currentYear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->whereDate('created_at', '<=', $today)
            ->where(function ($query){
                $query->where('date', 'like', '%Friday%');
                $query->orWhere('date', 'like', '%Saturday%');
            })
            ->where('student_id', '=', $student_id)
            ->orderBy('created_at', 'desc')
            ->take($absence_times)
            ->sum('daily_revision_grade');

        return max($normal_count - ($over_count_total - ($default_daily_revision_grade * $over_count)), 0);
    }

    // يومي الجمعة والسبت لازم يكون درجة الدرس الجديد أكبر من صفر فقط ولا يشترط أن تكون أكبر من الافتراضي
    $over_count_total_sat = Report::query()
        ->whereRaw('YEAR(created_at) = ?', [$currentYear])
        ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
        ->whereDate('created_at', '<=', $today)
        ->where(function ($query){
            $query->where('date', 'like', '%Friday%');
            $query->orWhere('date', 'like', '%Saturday%');
        })
        ->where('student_id', '=', $student_id)
        ->where('daily_revision_grade', '>', 0)
        ->sum('daily_revision_grade');

    // يومي الجمعة والسبت لا يشترط ان تكون جميع الدرجات فيه مكتملة انما كل درجة تقابلها تعوبض درجة من يوم أخر

    return max($normal_count - ( ( ($over_count_total - ($default_daily_revision_grade * $over_count)) / $default_daily_revision_grade ) + ($over_count_total_sat/$default_daily_revision_grade) ), 0);
    //          9            - (  ( ( 0               -  ( 2                        * 0         )) /  2                         ) + 0                    )      = 6
}

function getStudentDetails($student_id){
    return Cache::remember('get_student_details.' . $student_id, 60 * 60 * 24, function() use($student_id) {
        return \App\User::where('id', '=', $student_id)->first();
    });
}

function getRate($percentage, $lang){

    $message = [];
    if($percentage >= 90){
        $message = ['ar' => 'ممتاز', 'en' => 'Excellent'];
    }elseif ($percentage >= 80){
        $message = ['ar' => 'جيد جداً', 'en' => 'Very Good'];
    }elseif ($percentage >= 70){
        $message = ['ar' => 'جيد', 'en' => 'Good'];
    }elseif ($percentage >= 60){
        $message = $message = ['ar' => 'مقبول', 'en' => 'Pass'];
    }elseif ($percentage < 60){
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
        if ($date->year < $today->year){
            $status = true;
        }elseif( ($date->year == $today->year) && ($date->month < $today->month)){
            $status = true;
        } elseif( ($date->year <= $today->year) && ($date->month <= $today->month) && ($day <= $date->day || $day == $tomorrow->day) ) {
            $status = true;
        }
    }

    if(Auth::guard('teacher_web')->check()){

        $teacher_email = auth()->user()->email;

        $class_number = Cache::remember('get_class_number.' . request()->student_id, 60 * 60 * 24, function() {
            return \App\User::query()->find(request()->student_id)->class_number;
        });

//        $role = \App\ClassesTeachers::query()
//                ->where('teacher_email', '=', $teacher_email)
//                ->where('class_number', '=', $class_number)
//                ->first()->role ?? '';

        $role =  Cache::remember('role.' . $teacher_email . $class_number, 60 * 60 * 24, function() use($teacher_email, $class_number) {
            return  \App\ClassesTeachers::query()
                    ->where('teacher_email', '=', $teacher_email)
                    ->where('class_number', '=', $class_number)
                    ->first()->role ?? '';
        });

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

//    $myDate = '17/07/2022';
//    $tomorrow = Carbon::createFromFormat('m/d/Y', $myDate);

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

function isAvailableToSendMonthlyReport($month_year){

    if(env('ENABLE_MONTHLY_SEND') || env('ENABLE_PREVIOUS_MONTH')){
        return true;
    }

    if(!is_null($month_year)){
        $month = substr($month_year, -2);
        $year  = substr($month_year, 0, 4);
    }else{
        $month = date('m');
        $year  = date('Y');
    }

    $monthReport = Carbon::createFromDate($year, $month);
    $monthPeriod = $monthReport->format('Y-m');
    $first_day   = $monthReport->format('1');
    $lastDay     = $monthReport->format('t');

    $daysOfMonth = \Carbon\CarbonPeriod::between($monthPeriod.'-'.$first_day, $monthPeriod.'-'.$lastDay)->filter('isWeekday');

    $currentDate    = Carbon::createFromDate(date('Y'), date('m'), date('d'))->format('Y-m-d'); // 30-11-2022
    $lastWorkingDay = last($daysOfMonth->toArray())->format('Y-m-d'); // 30-11-201

    return ($currentDate >= $lastWorkingDay && $currentDate <= $monthReport->format('Y-m-t'));
}


function changeEnvironmentVariable($key, $value)
{
    clearCache();

    $path = base_path('.env');

    if(is_bool(env($key)))
    {
        $old = env($key) ? 'true' : 'false';
    }elseif(env($key)===null){
        $old = 'null';
    }else{
        $old = env($key);
    }

    if (file_exists($path)) {
        file_put_contents($path, str_replace(
            "$key=" . $old, "$key=".$value, file_get_contents($path)
        ));
    }

    clearCache();
}

function getReportMonth()
{
    $date = Carbon::now();

    if (env('ENABLE_PREVIOUS_MONTH')){
        $date_filter = $date->subMonth()->format('Y-m');
    }else{
        $date_filter = $date->format('Y-m');
    }

    return $date_filter;
}

function clearCache()
{
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
}

function getClassName($class_number)
{
    return \App\Classes::query()->where('class_number', $class_number)->first()->title ?? '';
}

function getPeriodTimeAvailable($data){
    $status = false;

//    $hour = Carbon::now()->timezone('Asia/Riyadh')->hour;
    $date = Carbon::now()->format('Y-m-d');
    $excuse_date = Carbon::createFromFormat('Y-m-d', $data['excuse_date'])->format('Y-m-d');

    if ($excuse_date <= $date){
        $status = true;
    }

//    switch ($data['period']){
//        case 1: $status = ($hour <= 7 && $date == $excuse_date) || ($date < $excuse_date); // 09:00 AM
//            break;
//        case 2: $status = ($hour <= 13 && $date == $excuse_date) || ($date < $excuse_date); // 03:00 PM
//            break;
//        case 3: $status = ($hour <= 17 && $date == $excuse_date) || ($date < $excuse_date); // 07:00 PM
//            break;
//        case 4: $status = ($hour <= 21 && $date == $excuse_date) || ($date < $excuse_date); // 11:00 PM
//            break;
//        case 5:
//            $status = ($hour <= 0 && $date == $excuse_date) || ($date < $excuse_date); // 02:00 AM
//            break;
//    }


    return $status;
}

function checkAbleToUpdateMonthlyScores($report)
{
    $report = Carbon::parse($report->report_date);

    $report_date  = $report->format('Y-m-d');
    $report_month = $report->format('m');
    $report_year  = $report->format('Y');

    $day   = env('SPECIFIC_DAY');
    $month = substr(env('SPECIFIC_MONTH_YEAR'), -2);
    $year  = substr(env('SPECIFIC_MONTH_YEAR'), 0, 4);

    if(is_numeric($day) && $report_month == $month && $report_year == $year){
        $day = intval(env('SPECIFIC_DAY'));
        $date = Carbon::createFromDate($year, $month, $day); // SPECIFIC DATE TO SEND = 18

        if ($date >= $report_date){
            return true;
        }

        return false;
    }

    return true;
}

function getAppropriateToday()
{

    $today = Carbon::today();

    $current_year  = $today->format('Y');
    $current_month = $today->format('m');

    $day   = env('SPECIFIC_DAY');
    $month = substr(env('SPECIFIC_MONTH_YEAR'), -2);
    $year  = substr(env('SPECIFIC_MONTH_YEAR'), 0, 4);

    if(is_numeric($day) && $current_month == $month && $current_year == $year){
        $day = intval(env('SPECIFIC_DAY'));
        $today = Carbon::createFromDate($year, $month, $day); // SPECIFIC DATE TO SEND = 18
    }

    return $today;
}

function isHasUserType($user_type)
{
    return auth()->user()->user_type == $user_type;
}

function getUserType()
{
    return auth()->user()->user_type;
}

function isOnlineStudent($student_id)
{
    // 0 is online, 1 is face to face
    return getStudentDetails($student_id)->study_type == 0;
}

function getLastDropoutNumber($options)
{
    return DropoutStudent::query()
            ->where('student_id', '=', $options['student_id'])
            ->where('dropout_count', '=', $options['dropout_count'] )
            ->count();
}

function dropoutCounts($student_id)
{
    $dropouts_student = DropoutStudent::query()->where('student_id', '=', $student_id);
    $dropout_count = clone $dropouts_student;
    $dropout_count = $dropout_count->max('dropout_count');
    return $dropouts_student->where('dropout_count', '=', $dropout_count)->count();
}

function getEmployeeInfo($worker_name, $column)
{
    $employees = Cache::remember('TopTrackerEmployees',60 * 60 * 60,function(){
        return TopTrackerEmployee::query()->get();
    });

    $employee = $employees->where('name', '=', $worker_name)->first();

    if ($employee){
        $employee = $employee->{$column};
    }else{
        $employee = '-';
    }

    return $employee;
}

function getStartTimePeriod($time)
{

    $periods = ['دخول الفترة الصباحية', 'دخول المسائية 1', 'دخول المسائية 2', 'دخول المسائية 3', 'دخول المسائية 4'];
    $time   = Carbon::parse($time)->setTimezone('Asia/Riyadh');

    $hour   = $time->hour;
    $minute = $time->minute;

    $period = '-';

    if ( ($hour >= 7 && $hour <= 10) || ($hour == 11 && $minute == 0) ){
        $period = 'دخول الفترة الصباحية';
    }elseif( ($hour == 13 && $minute >= 30) || ($hour >= 14 && $hour <= 16) || ($hour == 17 && $minute == 0) ){
        $period = 'دخول المسائية 1';
    }elseif( ($hour >= 18 && $hour <= 20) || ($hour == 21 && $minute == 0) ){
        $period = 'دخول المسائية 2';
    }elseif( ($hour == 21 && $minute >= 30) || ($hour >= 22 && $hour <= 23) || ($hour == 1 && $minute == 0) ) {
        $period = 'دخول المسائية 3';
    }elseif( ($hour == 1 && $minute >= 1) || ($hour >= 2 && $hour <= 3) || ($hour == 4 && $minute == 0) ) {
        $period = 'دخول المسائية 4';
    }

    return $period;
}

function getNoteParent($text)
{
    if (!is_null($text)){
        $note = \App\NoteParent::query()
            ->where('text', 'LIKE', '%' . $text . '%')
            ->orWhere('text_en', 'LIKE', '%' . $text . '%')
            ->first();
    }

    if (!is_null($text) && $note){
        return ['ar' => $note->text, 'en' => $note->text_en];
    }

    if ($text == 'Absent Student' || $text == 'الطالب غائب'){
        return ['ar' => 'الطالب غائب', 'en' => 'Absent Student'];
    }

    return ['ar' => $text, 'en' => $text];
}

function getAttendancePeriod($login_btn, $time)
{
    $period = '';
    if (isset($login_btn)){
        if($time >= '23:00' && $time <= '3:00'){
            $period = 'الفترة الصباحية';
        }

        if($time >= '5:30' && $time <= '9:00'){
            $period = 'الفترة المسائية 1';
        }

        if($time >= '10:00' && $time <= '13:00'){
            $period = 'الفترة المسائية 2';
        }

        if($time >= '13:30' && $time <= '17:00'){
            $period = 'الفترة المسائية 3';
        }

        if($time >= '17:01' && $time <= '20:00'){
            $period = 'الفترة المسائية 4';
        }
        session()->flash('success', 'تم تسجيل بصمة الدخول بنجاح');
    }else{
        if($time >= '1:55' && $time <= '6:00'){
            $period = 'الفترة الصباحية';
        }

        if($time >= '7:55' && $time <= '11:30'){
            $period = 'الفترة المسائية 1';
        }

        if($time >= '11:55' && $time <= '15:55'){
            $period = 'الفترة المسائية 2';
        }

        if($time >= '16:00' && $time <= '18:50'){
            $period = 'الفترة المسائية 3';
        }

        if($time >= '18:55' && $time <= '22:30'){
            $period = 'الفترة المسائية 4';
        }
        session()->flash('success', 'تم تسجيل بصمة الخروج بنجاح');
    }

    return $period;
}
