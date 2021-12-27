<?php

use App\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $nextMonth = $month + 1;
        $myDate = $nextMonth . '/01/2022';
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->day();
        $currentMonth = $month;
        $currentYear = 2021;
//        if (!empty(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path) && !is_null(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path)){
//            $path = \App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path;
//        }
    }else{
        $today = Carbon::tomorrow();
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
    if (isAchievedDefaultGrades($student_id) && $absence_times){
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

    $path = \App\User::find($student_id)->path;

    $current_month_year = Carbon::today()->format('Y-m');

    if ($current_month_year != $month_year && !is_null($month_year)){
        $previous_path = \App\MonthlyScore::query()->where('month_year', '=', $month_year)->where('user_id', '=', $student_id)->first()->path;
        if (!empty($previous_path)){
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
        $nextMonth = $month + 1;
        $myDate = $nextMonth . '/01/2022';
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->day();
        $currentMonth = $month;
        $currentYear = 2021;
        if (!empty(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path) && !is_null(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path)){
            $student_path = \App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path;
        }
    }else{
        $today = Carbon::tomorrow();
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
        $nextMonth = $month + 1;
        $myDate = $nextMonth . '/01/2022';
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->day();
        $currentMonth = $month;
        $currentYear = 2021;
    }else{
        $today = Carbon::tomorrow();
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
        ->where('absence', '!=', 0)
        ->where('absence', '!=', '-1')
        ->count();

    return (isAchievedDefaultGrades($student_id) && $absence_times > 0);
}

function getLessonsNotListenedCount($student_id, $month = false){

    $student_path = getStudentPath($student_id);

    if($month){
        $nextMonth = $month + 1;
        $myDate = $nextMonth . '/01/2022';
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->day();
        $currentMonth = $month;
        $currentYear = 2021;
        if (!empty(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path) && !is_null(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path)){
            $student_path = \App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path;
        }
    }else{
        $today = Carbon::tomorrow();
        $currentMonth = date('m');
        $currentYear = date('Y');

        if(request()->date_filter) {
            $currentMonth = substr(request()->date_filter, -2);
            $currentYear = substr(request()->date_filter, 0, 4);
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

    if(checkThirdCondition($student_id)){

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
        $nextMonth = $month + 1;
        $myDate = $nextMonth . '/01/2022';
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->day();
        $currentMonth = $month;
        $currentYear = 2021;
        if (!empty(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path) && !is_null(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path)){
            $student_path = \App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path;
        }
    }else{
        $today = Carbon::tomorrow();
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

    if(checkThirdCondition($student_id)){

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
        $nextMonth = $month + 1;
        $myDate = $nextMonth . '/01/2022';
        $today = Carbon::createFromFormat('m/d/Y', $myDate)->day();
        $currentMonth = $month;
        $currentYear = 2021;
        if (!empty(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path) && !is_null(\App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path)){
            $student_path = \App\MonthlyScore::query()->where('month_year', '=', date('Y') . '-' . $month)->where('user_id', '=', $student_id)->first()->path;
        }
    }else{
        $today = Carbon::tomorrow();
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
            ->where('absence', '=', 0)
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

    if(checkThirdCondition($student_id)){

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

        // عداد مرات الحضور في السبت والجمعة
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
        if( ($date->year == $today->year) && ($date->month < $today->month) && ($date->month <= $today->month) ) {
            $status = true;
        }elseif(($date->year == $today->year) && ($date->month == $today->month) && ($date->day <= $today->day)){
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

function isAvailableToSendMonthlyReport($month_year){

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

    $currentDate    = Carbon::createFromDate(date('Y'), date('m'), date('d'))->format('Y-m-d'); // 30-11-2021
    $lastWorkingDay = last($daysOfMonth->toArray())->format('Y-m-d'); // 30-11-201

    return ($currentDate >= $lastWorkingDay && $currentDate <= $monthReport->format('Y-m-t'));
}
