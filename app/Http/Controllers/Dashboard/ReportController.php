<?php


namespace App\Http\Controllers\Dashboard;

use App\Chapter;
use App\DataTables\ReportDatatable;
use App\Events\ReportUpdated;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\LessonPage;
use App\Mail\ReportMail;
use App\NooraniaPage;
use App\NoteParent;
use App\Notifications\userReportMonthlyNotification;
use App\Report;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{

    public function reportTable()
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        if(request()->date_filter){
            $month = substr(request()->date_filter, -2);
            $year = substr(request()->date_filter, 0, 4);
            $now = new Carbon($year . '-' . $month);
        }

        $reports = Report::query()->where('student_id', '=', request()->student_id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->get();
        $notes = NoteParent::query()->where('gender', '=', User::query()->where('id', '=', request()->student_id)->first()->section)->get();

        $new_lessons = Cache::remember('new_lessons',60 * 60 * 60,function(){
            return Lesson::query()->get();
        });

        $daily_revision = Cache::remember('daily_revision',60 * 60 * 60,function(){
            return Chapter::query()->get();
        });

        $class_number = User::query()->where('id', '=', request()->student_id)->first()->class_number;
        $students = User::query()->where('class_number', '=', $class_number)->orderBy('student_number', 'ASC')->get();

        $user_student = User::with('monthlyScores')->where('users.id', '=', request()->student_id)->first();

        $lesson_pages = Cache::remember('lesson_pages',60 * 60 * 60,function(){
            return LessonPage::query()->get();
        });

        return view('admins.reports.monthly_table', ['now' => $now, 'month' => $month, 'reports' => $reports, 'notes' => $notes, 'students' => $students, 'new_lessons' => $new_lessons, 'daily_revision' => $daily_revision, "user_student" => $user_student, "lesson_pages" => $lesson_pages, 'year' => $year]);
    }

    public function reportTableStore(Request $request)
    {
        if($request->type == 'lessons'){
            $report = Report::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'date' => $request->date,
                    'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                ],
                [
                    'new_lesson' => $this->getValidData($request->new_lesson, 'new_lesson'),
                    'new_lesson_from' => $this->getValidData($request->new_lesson_from, 'new_lesson_from'),
                    'new_lesson_to' => $this->getValidData($request->new_lesson_to, 'new_lesson_to'),
                    'last_5_pages' => $this->getValidData($request->last_5_pages, 'last_5_pages'),
                    'daily_revision' => $this->getValidData($request->daily_revision, 'daily_revision'),
                    'daily_revision_from' => $this->getValidData($request->daily_revision_from, 'daily_revision_from'),
                    'daily_revision_to' => $this->getValidData($request->daily_revision_to, 'daily_revision_to'),
                    'mistake' => $this->getValidData($request->mistake, 'mistake'),
                    'alert' => $this->getValidData($request->alert, 'alert'),
                    'number_pages' => $this->getValidData($request->number_pages, 'number_pages'),
                    'listener_name' => $this->getValidData($request->listener_name, 'listener_name'),
                ]
            );

        }

        if($request->type == 'grades'){

            $current_month_year = Carbon::today()->format('Y-m');
            $report_month_year  = Carbon::parse($request->created_at)->format('Y-m');

            $student_path = getStudentPath($request->student_id);
            if ($current_month_year != $report_month_year) {
                $student_id = $request->student_id;
                if (!empty(\App\MonthlyScore::query()->where('month_year', '=', $report_month_year)->where('user_id', '=', $student_id)->first()->path) && !is_null(\App\MonthlyScore::query()->where('month_year', '=', $report_month_year)->where('user_id', '=', $student_id)->first()->path)){
                    $student_path = \App\MonthlyScore::query()->where('month_year', '=', $report_month_year)->where('user_id', '=', $student_id)->first()->path;
                }
            }

//            $grades = [
//                'lesson_grade'         => $request->lesson_grade,
//                'last_5_pages_grade'   => $request->last_5_pages_grade,
//                'daily_revision_grade' => $request->daily_revision_grade,
//                'behavior_grade'       => $request->behavior_grade
//            ];
            $default_grade = [
                'lesson_grade'         => getPathDefaultGrade($student_path, 'new_lesson'),
                'last_5_pages_grade'   => getPathDefaultGrade($student_path, 'last_5_pages'),
                'daily_revision_grade' => getPathDefaultGrade($student_path, 'daily_revision'),
                'behavior_grade'       => getPathDefaultGrade($student_path, 'behavior'),
            ];

//            foreach ($grades as $key => $grade){
//                if (is_numeric($grade) && $grade > $default_grade[$key]*2){
//                    return response()->json(['error' => "يرجى إدخال درجة صحيحة"], 400);
//                }
//            }

            $total = 0;

            if ($request->notes_to_parent == 'الطالب غائب' || $request->notes_to_parent == 'Absent Student'){
                $absence_grade = -5;
                if (getStudentDetails(request()->student_id)->path == 'قسم التلاوة'){
                    $absence_grade = getAbsenceCount($request->student_id, -2) >= 8 ? -5 : -2;
                }

                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date'       => $request->date,
                        'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => 'غ',
                        'last_5_pages_grade' => 0,
                        'daily_revision_grade' => 0,
                        'behavior_grade' => 0,
                        'notes_to_parent' => $request->notes_to_parent,
                        'absence' => $absence_grade,
                        'total' => $total,
                        'mail_status' => 0,
                        'class_number' => getStudentDetails(request()->student_id)->class_number,
                    ]
                );
            }elseif($request->notes_to_parent == 'دوام 3 أيام' || $request->notes_to_parent == '3 days work'){
                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date'       => $request->date,
                        'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => 'غ',
                        'last_5_pages_grade' => '-',
                        'daily_revision_grade' => '-',
                        'behavior_grade' => '-',
                        'notes_to_parent' => $request->notes_to_parent,
                        'absence' => '-1',
                        'total' => $total,
                        'mail_status' => 0,
                        'class_number' => getStudentDetails(request()->student_id)->class_number,
                    ]
                );
            }elseif($request->notes_to_parent == 'نشاط لا صفي' || $request->notes_to_parent == 'Extracurricular Activity'){
                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date' => $request->date,
                        'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => $default_grade['lesson_grade'],
                        'last_5_pages_grade' => $default_grade['last_5_pages_grade'],
                        'daily_revision_grade' => $default_grade['daily_revision_grade'],
                        'behavior_grade' => $default_grade['behavior_grade'],
                        'notes_to_parent' => $request->notes_to_parent,
                        'absence' => 0,
                        'total' => $default_grade['lesson_grade'] + $default_grade['last_5_pages_grade'] + $default_grade['daily_revision_grade'] + $default_grade['behavior_grade'],
                        'mail_status' => 0,
                        'class_number' => getStudentDetails(request()->student_id)->class_number,
                    ]
                );
            }else{

                $request->absence = 0;
                $total = $this->sumTotal([
                    $request->lesson_grade,
                    $request->last_5_pages_grade,
                    $request->daily_revision_grade,
                    $request->behavior_grade
                ]);

                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date' => $request->date,
                        'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => $this->getValidGrade($request->lesson_grade, 'lesson_grade') == 'غ' ? '' : $this->getValidGrade($request->lesson_grade, 'lesson_grade'),
                        'last_5_pages_grade' => $this->getValidGrade($request->last_5_pages_grade, 'last_5_pages_grade'),
                        'daily_revision_grade' => $this->getValidGrade($request->daily_revision_grade, 'daily_revision_grade'),
                        'behavior_grade' => $this->getValidGrade($request->behavior_grade, 'behavior_grade'),
                        'notes_to_parent' => $request->notes_to_parent,
                        'absence' => $request->absence,
                        'total' => $total,
                    ]
                );

            }

//            Event::dispatch(new ReportUpdated($report));
        }

        return response()->json(['report' => $report], 200);
    }

    public function exportIndex()
    {
        return view('admins.import_export.export_reports');
    }

    public function exportStore(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        if (!isset($request->mail_status)){
            $request->mail_status = 2;
        }

        if (!isset($request->study_type)){
            $request->study_type = 2;
        }

        $title = 'التقارير اليومية للطلاب';
        switch ($request->study_type){
            case 2:
                $title = 'التقارير اليومية لجميع الطلاب';
                break;
            case 1:
                $title = 'التقارير اليومية لطلاب الحضوري';
                break;
            case 0:
                $title = 'التقارير اليومية لطلاب الاونلاين';
                break;
        }

        return Excel::download(new ReportsExport($request->date_from, $request->date_to, $request->mail_status, $request->study_type), $title . '.xlsx');
    }

    public function sendReport($grades = null, $assignment){

        if(is_null($grades)){
            $student = User::where('id', '=', $assignment->student_id)->first();
            if($student->father_mail == $student->mother_mail){
                $to_mails = $student->father_mail;
            }else{
                $to_mails = [$student->father_mail, $student->mother_mail];
            }

            $nameTitle = $student->section == 'male' ? '' : 'ة';

            $details = [
                'title' => 'التقرير اليومي للطالب' . $nameTitle . ' ' . $student->name . ' - ' . $student->student_number . ' Daily Report',
                'subject' => 'التقرير اليومي للطالب' . $nameTitle . ' ' . $student->name . ' - ' . $student->student_number . ' Daily Report',
                'grades' => $grades,
                'assignment' => $assignment,
                'student_info' => $student,
            ];
            Mail::to($to_mails)->send(new ReportMail($details));
        }else{
            $student = User::where('id', '=', $grades->student_id)->first();
            if($student->father_mail == $student->mother_mail){
                $to_mails = $student->father_mail;
            }else{
                $to_mails = [$student->father_mail, $student->mother_mail];
            }

            $nameTitle = $student->section == 'male' ? '' : 'ة';
            $details = [
                'title' => 'التقرير اليومي للطالب' . $nameTitle . ' ' . $student->name . ' - ' . $student->student_number . ' Daily Report',
                'subject' => 'التقرير اليومي للطالب' . $nameTitle . ' ' . $student->name . ' - ' . $student->student_number . ' Daily Report',
                'grades' => $grades,
                'assignment' => $assignment,
                'student_info' => $student,
            ];
        }

        $currentMonth = date('m');

        $monthly_report_statistics = Report::whereRaw('MONTH(created_at) = ?', [$currentMonth])
                                            ->where('student_id', '=', $assignment->student_id);

        $monthly_report = $monthly_report_statistics->get();

        $now = Carbon::now();
        $month = $now->month;

        $pdf = PDF::loadView('teachers.emails.monthly_report',
            [
                'student_id' => $assignment->student_id,
                'month' => $currentMonth,
                'monthly_report' => $monthly_report,
                'monthly_report_statistics' => $monthly_report_statistics,
                'now' => $now,
                'month' => $month,
            ], [], [
                'format' => 'A2'
            ]);

        return $pdf->stream('document.pdf');

        $report = Report::where('id', '=', $grades->id)->first();
        $report->update(['mail_status' => 1]);

        Mail::to($to_mails)
            ->bcc(['abdhafez.1996@gmail.com'])
            ->send(new ReportMail($details, $pdf));
    }

    public function sendReportTable(Request $request)
    {
        $tomorrow = Carbon::tomorrow();
        $today = Carbon::today();

        if(str_contains($tomorrow->format('l') ,'Friday')){
            $tomorrow->addDays(2);
        }

        $grades_report = Report::query()
            ->where('student_id', '=', $request->student_id)
            ->whereMonth('created_at', '=', $today->month)
            ->whereDay('created_at', '=', $today->day)
            ->whereYear('created_at', '=', $today->year)
            ->whereNotNull('lesson_grade')
            ->where('lesson_grade', '!=', '')
            ->whereNotNull('last_5_pages_grade')
//                        ->where('last_5_pages_grade', '!=', '')
            ->whereNotNull('behavior_grade')
//                        ->where('behavior_grade', '!=', '')
            ->whereNotNull('daily_revision_grade')
//                        ->where('daily_revision_grade', '!=', '')
            ->whereNotNull('listener_name')
//                        ->where('listener_name', '!=', '')
            ->whereNotNull('mistake')
            ->where('mistake', '!=', '')
            ->whereNotNull('alert')
            ->where('alert', '!=', '')
            ->first();

        $lessons_report = Report::query()
            ->where('student_id', '=', $request->student_id)
            ->whereMonth('created_at', '=', $tomorrow->month)
            ->whereDay('created_at', '=', $tomorrow->day)
            ->whereYear('created_at', '=', $tomorrow->year)
            ->whereNotNull('new_lesson')
            ->where('new_lesson', '!=', '')
            ->whereNotNull('new_lesson_from')
            ->where('new_lesson_from', '!=', '')
            ->whereNotNull('new_lesson_to')
            ->where('new_lesson_to', '!=', '')
            ->whereNotNull('daily_revision')
            ->where('daily_revision', '!=', '')
            ->whereNotNull('daily_revision_from')
            ->where('daily_revision_from', '!=', '')
            ->whereNotNull('daily_revision_to')
            ->where('daily_revision_to', '!=', '')
            ->whereNotNull('number_pages')
            ->where('number_pages', '!=', '')
            ->first();

        if(is_null($grades_report) || is_null($lessons_report)){
            session()->flash('error', 'لم يتم ارسال التقرير اليومي يرجى التأكد من إدخال جميع البيانات!!');
            return redirect()->route('admins.report.table', $request->student_id);
        }

        $this->sendReport($grades_report, $lessons_report);
        session()->flash('success', 'تم ارسال التقرير اليومي بنجاح');
        if(request()->date_filter) {
            return redirect()->route('admins.report.table', $request->student_id . '?date_filter=' . request()->date_filter);
        }
        return redirect()->route('admins.report.table', $request->student_id);
    }

    public function fireUpdateMonthlyScoresEvent(Request $request)
    {
        if (checkAbleToUpdateMonthlyScores($request)){
            $class_number = User::query()->find($request->student_id)->class_number;
            $report = ['created_at' => $request->created_at, 'student_id' => $request->student_id, 'class_number' => $class_number];
            Event::dispatch(new ReportUpdated($report));
        }

    }

    public function changePageNumber(Request $request){

        $student_path = getStudentPath($request->student_id, $request->month_year) ?? getStudentPath($request->student_id);
        $student =  User::query()->find($request->student_id);;

        $is_hejaa = false;
        if($student_path == "قسم الهجاء"){
            $is_hejaa = true;
            $lesson = NooraniaPage::find($request->page_number_id);
        }else{
            $lesson = LessonPage::find($request->page_number_id);
        }

        $current_month_year = Carbon::today()->format('Y-m');

        if ($current_month_year != $request->month_year){
            DB::table('monthly_scores')->updateOrInsert(
                [
                    'user_id' => $request->student_id,
                    'month_year' => $request->month_year,
                ],
                [
                    'lesson_page_id'   => $is_hejaa ? null : $request->page_number_id,
                    'noorania_page_id' => $is_hejaa ? $request->page_number_id : null,
                ]
            );
        }else{
            DB::table('monthly_scores')->updateOrInsert(
                [
                    'user_id' => $request->student_id,
                    'month_year' => $request->month_year,
                ],
                [
                    'lesson_page_id'   => $is_hejaa ? null : $request->page_number_id,
                    'noorania_page_id' => $is_hejaa ? $request->page_number_id : null,
                    'path'             => $student_path,
                    'class_number'     => $student->class_number,
                ]
            );
        }

        return response()->json(['lesson_title' => $lesson->lesson_title], 201);
    }

    public function getWorkingDaysCount($month, $year)
    {

        $currentMonth = Carbon::createFromDate($year, $month)->format('F');
        $nextMonth = Carbon::createFromDate($year, $month)->addMonth();

        $holidays[] = new \DatePeriod(
            Carbon::parse("first saturday of $currentMonth $year"),
            CarbonInterval::week(),
            Carbon::parse("first day of " . $nextMonth->format('F') . " " . $nextMonth->year)
        );

        $holidays[] = new \DatePeriod(
            Carbon::parse("first friday of $currentMonth $year"),
            CarbonInterval::week(),
            Carbon::parse("first friday of " . $nextMonth->format('F') . " " . $nextMonth->year)
        );

        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $holidaysCont = 0;

        foreach ($holidays as $key => $days){
            $holidaysCont += iterator_count($days);
        }

        return $daysInMonth - $holidaysCont;
    }

    public function checkMonthlyReportInfo($user, $month_year)
    {

        $month = substr($month_year, -2);
        $year  = substr($month_year, 0, 4);

        if ($user->path == "قسم الهجاء"){
            $pageNumber = $user->monthlyScores($month_year)->noorania_page_id ?? false;
        }else{
            $pageNumber = $user->monthlyScores($month_year)->lesson_page_id ?? false;
        }

        $is_new_student = false;
        $previous_date = Carbon::createFromDate($year, $month)->subMonth();
        $previous_month      = $previous_date->month;
        $previous_month_year = $previous_date->year;

        $is_old_student = Report::query()
            ->whereMonth('created_at', '=', $previous_month)
            ->whereYear('created_at', '=', $previous_month_year)
            ->where('student_id', '=', $user->id)
            ->count();

        if($is_old_student){
            $reportsCount = Report::query()
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->where('date', 'not like', '%Saturday%')
                ->where('date', 'not like', '%Friday%')
                ->where('student_id', '=', $user->id)
                ->where(function ($query){
                    $query ->where('lesson_grade', '>=', 0);
                    $query ->orWhere('lesson_grade', '=', '-');
                })->where(function ($query){
                    $query ->where('last_5_pages_grade', '>=', 0);
                    $query ->orWhere('last_5_pages_grade', '=', '-');
                })->where(function ($query){
                    $query ->where('daily_revision_grade', '>=', 0);
                    $query ->orWhere('daily_revision_grade', '=', '-');
                })->whereIn('absence', [0, -2, -5, -1])
                ->count();

            $workingDays = $this->getWorkingDaysCount($month, $year);
        }else{
            $first_report_this_month = Report::query()
                    ->whereMonth('created_at', '=', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where('date', 'not like', '%Saturday%')
                    ->where('date', 'not like', '%Friday%')
                    ->where('student_id', '=', $user->id)
                    ->first()->created_at ?? false;

            if (!$first_report_this_month){
                return false;
            }

            $reportsCount = Report::query()
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->where('date', 'not like', '%Saturday%')
                ->where('date', 'not like', '%Friday%')
                ->where('student_id', '=', $user->id)
                ->where(function ($query){
                    $query ->where('lesson_grade', '>=', 0);
                    $query ->orWhere('lesson_grade', '=', '-');
                })->where(function ($query){
                    $query ->where('last_5_pages_grade', '>=', 0);
                    $query ->orWhere('last_5_pages_grade', '=', '-');
                })->where(function ($query){
                    $query ->where('daily_revision_grade', '>=', 0);
                    $query ->orWhere('daily_revision_grade', '=', '-');
                })->whereIn('absence', [0, -2, -5, -1])
                ->count();

            $daysOfMonth = \Carbon\CarbonPeriod::between($first_report_this_month->year. '-' . $first_report_this_month->month . '-'.$first_report_this_month->day, $first_report_this_month->year. '-' . $first_report_this_month->month . '-' .$first_report_this_month->format('t'))->filter('isWeekday');
            $workingDays = count($daysOfMonth->toArray()); // 14
        }

        if($reportsCount < $workingDays){
            $all_days_filled = false;
        }else{
            $all_days_filled = true;
        }

        return ($pageNumber && $all_days_filled);
    }

    public function sendReportTableMonthly(Request $request)
    {
        $user =  User::find($request->student_id);

        if(!isset($request->date_filter) || is_null($request->date_filter)) {
            $request['date_filter'] = date('Y') . '-' . date('m');
        }

        if (!$this->checkMonthlyReportInfo($user, $request->date_filter)){
            session()->flash('error', 'يرجى التأكد من إدخال جميع بيانات الشهر ورقم الصفحة بشكل صحيح!');
            return redirect()->route('admins.report.table', $request->student_id);
        }

        try{
            Notification::route('mail', [$user->father_mail, $user->mother_mail])->notify(new userReportMonthlyNotification($user, $request->date_filter));
            $report = $user->monthlyScores($request->date_filter);
            $report->update(['mail_status' => 1]);
        }
        catch(\Exception $e){
            session()->flash('error', 'فشلت عملية ارسال التقرير الشهري!');
            return redirect()->route('admins.report.table', $request->student_id);
        }

        session()->flash('success', 'تم ارسال التقرير الشهري بنجاح');

        return redirect()->route('admins.report.table', $request->student_id);
    }

    public function getValidData($string, $col_name)
    {
        $tomorrow_string = Carbon::tomorrow();
        $today = Carbon::today()->format('Y-m-d');
        if(str_contains($tomorrow_string->format('l') ,'Friday')){
            $tomorrow_string->addDays(2);
        }
        $tomorrow = $tomorrow_string->format('Y-m-d');

        $today_mail_status = Report::query()->where('student_id', '=', request()->student_id)->where('created_at', 'LIKE', $today . ' %')->first()->mail_status ?? 0;

        if($today_mail_status){
            if($tomorrow == request()->created_at){
                if(isset($string) && !is_null($string)){
                    return $string;
                }
                return Report::query()->where('student_id', '=', request()->student_id)->where('created_at', 'LIKE', request()->created_at . ' %')->first()->$col_name ?? '';
            }

            if(isset($string) && !is_null($string)){
                return $string;
            }
            return Report::query()->where('student_id', '=', request()->student_id)->where('created_at', 'LIKE', $today . ' %')->first()->$col_name ?? '';
        }

        return $string;
    }

    public function getValidGrade($string, $col_name)
    {
        $tomorrow_string = Carbon::tomorrow();
        $today = Carbon::today()->format('Y-m-d');
        if(str_contains($tomorrow_string->format('l') ,'Friday')){
            $tomorrow_string->addDays(2);
        }
        $tomorrow = $tomorrow_string->format('Y-m-d');

        $today_mail_status = Report::query()->where('student_id', '=', request()->student_id)->where('created_at', 'LIKE', $today . ' %')->first()->mail_status ?? 0;

        if($today_mail_status){
            if($tomorrow == request()->created_at){
                if(isset($string) && !is_null($string)){
                    return $string;
                }
                return Report::query()->where('student_id', '=', request()->student_id)->where('created_at', 'LIKE', request()->created_at . '%')->first()->$col_name ?? '';
            }

            if(is_numeric($string)){
                return $string;
            }
            return Report::query()->where('student_id', '=', request()->student_id)->where('created_at', 'LIKE', request()->created_at . '%')->first()->$col_name ?? '';
        }

        return $string;
    }

    public function sumTotal($values)
    {
        $total = 0;
        foreach ($values as $value){
            if(is_numeric($value)){
                $total+=$value;
            }
        }
        return $total;
    }

}
