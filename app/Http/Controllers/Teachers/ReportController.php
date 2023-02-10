<?php

namespace App\Http\Controllers\Teachers;
use App\Chapter;
use App\ClassesTeachers;
use App\Events\ReportUpdated;
use App\Http\Controllers\Controller;
use App\Jobs\TeacherNotify;
use App\Lesson;
use App\LessonPage;
use App\Mail\ReportMail;
use App\NooraniaPage;
use App\NoteParent;
use App\Notifications\TecherReport;
use App\Notifications\userReportMonthlyNotification;
use App\Report;
use App\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{

    public function reportTable()
    {
        $now = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        if(request()->date_filter){
            $month = substr(request()->date_filter, -2);
            $year = substr(request()->date_filter, 0, 4);
            $now = new Carbon($year . '-' . $month);
        }

        $reports = Report::query()->where('student_id', '=', request()->student_id)->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->get();

        $notes = NoteParent::query()->where('gender', '=', User::query()->where('id', '=', request()->student_id)->first()->section)->get();

        $class_number = User::query()->where('id', '=', request()->student_id)->first()->class_number;

        $students = Cache::remember('class_students.' . $class_number,60 * 60 * 24, function() use ($class_number){
            return User::query()->where('class_number', '=', $class_number)->orderBy('student_number', 'ASC')->get();
        });

        $students_listener_names = Cache::remember('students_listener_names.' . $class_number,60 * 60 * 24, function() use ($class_number) {
            return User::query()
                ->where('users.class_number', '=', $class_number)
                ->select('name')
                ->get()
                ->pluck('name')
                ->toArray();
        });

        $teachers_listener_names = Cache::remember('teachers_listener_names.' . $class_number,60 * 60 * 24, function() use ($class_number) {
            return Teacher::query()
                ->join('classes_teachers', 'classes_teachers.teacher_email', '=', 'teachers.email')
                ->where('classes_teachers.class_number', '=', $class_number)
                ->select('teachers.name')
                ->get()
                ->pluck('name')
                ->toArray();
        });

        $listener_names = array_merge($students_listener_names, $teachers_listener_names);

        $user_student = User::with('monthlyScores')->where('users.id', '=', request()->student_id)->first();

        $lesson_pages = Cache::remember('lesson_pages',60 * 60 * 48,function(){
            return LessonPage::query()->get();
        });

        $noorania_pages = Cache::remember('noorania_pages',60 * 60 * 48,function(){
            return NooraniaPage::query()->get();
        });

        $new_lessons = Cache::remember('new_lessons',60 * 60 * 48,function(){
            return Lesson::query()->get();
        });

        $daily_revision = Cache::remember('daily_revision',60 * 60 * 48,function(){
            return Chapter::query()->get();
        });

        return view('teachers.reports.monthly_table', ['now' => $now, 'month' => $month, 'reports' => $reports, 'notes' => $notes, 'students' => $students, 'new_lessons' => $new_lessons, 'daily_revision' => $daily_revision, 'listener_names' => $listener_names, 'user_student' => $user_student, "lesson_pages" => $lesson_pages, 'year' => $year, 'noorania_pages' => $noorania_pages]);
    }

    public function reportTableStore(Request $request)
    {

        $report  = Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first();

        if($request->type == 'lessons'){
            $report = Report::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'date' => $request->date,
                    'created_at' => $report->created_at ?? $request->created_at
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
                    'class_number' => getStudentDetails(request()->student_id)->class_number,

                    'entry_time' => $request->entry_time ? Carbon::parse($request->entry_time)->format('H:i') : Carbon::parse(@$report->student->login_time)->format('H:i'),
                    'exit_time' => $request->exit_time ? Carbon::parse($request->exit_time)->format('H:i') : Carbon::parse(@$report->student->exit_time)->format('H:i'),

                    'sitting_status' => $request->sitting_status == 'true' ? '1' : '0',
                    'camera_status' => $request->camera_status == 'true' ? '1' : '0',
                    'mail_status' => 0,
                ]
            );
        }

        if($request->type == 'grades'){

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
                        'created_at' => $report->created_at ?? $request->created_at
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
                $student_path = getStudentPath($request->student_id);
                $default_grade = [
                    'lesson_grade'         => getPathDefaultGrade($student_path, 'new_lesson'),
                    'last_5_pages_grade'   => getPathDefaultGrade($student_path, 'last_5_pages'),
                    'daily_revision_grade' => getPathDefaultGrade($student_path, 'daily_revision'),
                    'behavior_grade'       => getPathDefaultGrade($student_path, 'behavior'),
                ];

                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date' => $request->date,
                        'created_at' => $report->created_at ?? $request->created_at
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
                        'created_at' => $report->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => $this->getValidGrade($request->lesson_grade, 'lesson_grade') == 'غ' ? '' : $this->getValidGrade($request->lesson_grade, 'lesson_grade'),
                        'last_5_pages_grade' => $this->getValidGrade($request->last_5_pages_grade, 'last_5_pages_grade'),
                        'daily_revision_grade' => $this->getValidGrade($request->daily_revision_grade, 'daily_revision_grade'),
                        'behavior_grade' => $this->getValidGrade($request->behavior_grade, 'behavior_grade'),
                        'notes_to_parent' => $request->notes_to_parent,
                        'absence' => $request->absence,
                        'total' => $total,
                        'mail_status' => 0,
                        'class_number' => getStudentDetails(request()->student_id)->class_number,
                    ]
                );
            }

        }

        return response()->json(['report' => $report], 200);
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

    public function getWorkingDaysCount($year, $month, $start_day)
    {
        $start = Carbon::createFromDate($year, $month, $start_day);

        if(is_numeric(env('SPECIFIC_DAY'))){
            $end_day = intval(env('SPECIFIC_DAY'));
        }else{
            $end_day = Carbon::createFromDate($year, $month)->daysInMonth;
        }

        $end = Carbon::createFromDate($year, $month, $end_day);
        $daysInMonth = $end->diffInDays($start);

        $holidaysCont = 0;
        while ($start->lte($end)) {
            $carbon = Carbon::parse($start);
            if ($carbon->isWeekend()) {
                $holidaysCont++;
            }
            $start->addDay();
        }

        return $daysInMonth - $holidaysCont;
    }

    public function checkMonthlyReportInfo($user, $month_year)
    {

        $month = substr($month_year, -2);
        $year  = substr($month_year, 0, 4);

        if(is_numeric(env('SPECIFIC_DAY'))){
            $day = intval(env('SPECIFIC_DAY'));
        }else{
            $day = Carbon::createFromDate($year, $month)->daysInMonth;
        }

        if (empty($user->monthlyScores->first()->path)){
            $user_path = $user->path;
        }else{
            $user_path = $user->monthlyScores->first()->path;
        }

        if ($user_path == "قسم الهجاء"){
            $pageNumber = $user->monthlyScores()->first()->noorania_page_id ?? false;
        }else{
            $pageNumber = $user->monthlyScores()->first()->lesson_page_id ?? false;
        }

        $previous_date = Carbon::createFromDate($year, $month)->subMonth(); // subtract one month from current month
        $previous_month      = $previous_date->month;
        $previous_month_year = $previous_date->year;

        $is_old_student = Report::query()
            ->whereMonth('created_at', '=', $previous_month)
            ->whereYear('created_at', '=', $previous_month_year)
            ->where('student_id', '=', $user->id)
            ->count(); // if this student has any previous reports in last month the count will be more than 0

        // if is old student
        if($is_old_student){
            $reportsCount = Report::query()
                ->whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->whereDay('created_at', '<=', $day)
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

            $workingDays = $this->getWorkingDaysCount($year, $month, 1);
        }else{
            $first_report_this_month = Report::query()
                    ->whereMonth('created_at', '=', $month)
                    ->whereYear('created_at', '=', $year)
                    ->whereDay('created_at', '<=', $day)
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
                ->whereDay('created_at', '<=', $day)
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

            $workingDays = $this->getWorkingDaysCount($first_report_this_month->year, $first_report_this_month->month, $first_report_this_month->day);
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
        $user = User::with('monthlyScores')->where('users.id', '=', $request->student_id)->first();

        if(is_null($request->date_filter)) {
            $request['date_filter'] = date('Y') . '-' . date('m');
        }

        if (!$this->checkMonthlyReportInfo($user, $request->date_filter)){
            session()->flash('error', 'يرجى التأكد من إدخال جميع بيانات الشهر ورقم الصفحة بشكل صحيح!');
            return redirect()->route('teachers.report.table', $request->student_id . '?date_filter=' . $request['date_filter']);
        }

        try{
            $to_mails = [];
            if(filter_var($user->father_mail, FILTER_VALIDATE_EMAIL)){
                $to_mails[] = $user->father_mail;
            }

            if(filter_var($user->mother_mail, FILTER_VALIDATE_EMAIL)){
                $to_mails[] = $user->mother_mail;
            }

            Notification::route('mail', $to_mails)->notify(new userReportMonthlyNotification($user, $request->date_filter));
            $report = $user->monthlyScores()->first();
            $report->update(['mail_status' => '1']);
        }catch(\Exception $e){
            session()->flash('error', 'فشلت عملية ارسال التقرير الشهري!');
            return redirect()->route('teachers.report.table', $request->student_id);
        }

        session()->flash('success', 'تم ارسال التقرير الشهري بنجاح');

        return redirect()->route('teachers.report.table', $request->student_id);
    }

    public function sendReportTable(Request $request)
    {

        if (!request()->notes_to_parent){
            session()->flash('error', 'لم يتم ارسال التقرير اليومي، يرجى التأكد من إدخال بيانات اليوم الحالي بشكل صحيح من صفحة الشهر!!');
            return redirect()->route('teachers.report.table', $request->student_id);
        }

        $notes_to_parents = request()->notes_to_parent[0];

        $notes_to_parents_values = ['الطالب غائب', 'دوام 3 أيام', 'نشاط لا صفي', 'Absent Student', '3 days work', 'Extracurricular Activity'];

        $request->validate([
            'new_lesson' => 'required|array',
            'new_lesson.*' => 'required|string|min:1',
            'new_lesson_from' => 'required|array',
            'new_lesson_from.*' => 'required',
            'new_lesson_to' => 'required|array',
            'new_lesson_to.*' => 'required',
            'last_5_pages' => 'required|array',
            'last_5_pages.*' => 'required',
            'daily_revision' => 'required|array',
            'daily_revision.*' => 'required|string|min:1',
            'daily_revision_from' => 'required|array',
            'daily_revision_from.*' => 'required',
            'daily_revision_to' => 'required|array',
            'daily_revision_to.*' => 'required',
            'mistake.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required' ,
            'alert.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required' ,
            'number_pages' => 'required',
            'number_pages.*' => 'required',
            'listener_name.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required|string' ,
            'lesson_grade.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required|numeric' ,
            'last_5_pages_grade.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required' ,
            'daily_revision_grade.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required' ,
            'behavior_grade.' . 0  => in_array($notes_to_parents, $notes_to_parents_values) ? '' : 'required' ,
        ], [
            'new_lesson.' . 0 . '.required' => '"الدرس الجديد" لهذا اليوم غير مدخل',
            'new_lesson.' . 1 . '.required' => '"الدرس الجديد" ليوم غد غير مدخل',
            'new_lesson_from.' . 0 . '.required' => '"الدرس الجديد من" لهذا اليوم غير مدخل',
            'new_lesson_from.' . 1 . '.required' => '"الدرس الجديد من" ليوم غد غير مدخل',
            'new_lesson_to.' . 0 . '.required' => '"الدرس الجديد إلى" لهذا اليوم غير مدخل',
            'new_lesson_to.' . 1 . '.required' => '"الدرس الجديد إلى" ليوم غد غير مدخل',
            'last_5_pages.' . 0 . '.required' => '"أخر 5 صفحات" لهذا اليوم غير مدخلة',
            'last_5_pages.' . 1 . '.required' => '"أخر 5 صفحات" ليوم غد غير مدخلة',
            'daily_revision.' . 0 . '.required' => '"المراجعة اليومية" لهذا اليوم غير مدخلة',
            'daily_revision.' . 1 . '.required' => '"المراجعة اليومية" ليوم غد غير مدخلة',
            'daily_revision_from.' . 1 . '.required' => '"المراجعة اليومية من" ليوم غد غير مدخلة',
            'daily_revision_from.' . 0 . '.required' => '"المراجعة اليومية من" لهذا اليوم غير مدخلة',
            'daily_revision_to.' . 0 . '.required' => '"المراجعة اليومية إلى" لهذا اليوم غير مدخلة',
            'daily_revision_to.' . 1 . '.required' => '"المراجعة اليومية إلى" ليوم غد غير مدخلة',
            'number_pages.' . 0 . '.required' => '"عدد الصفحات" لهذا اليوم غير مدخل',
            'number_pages.' . 1 . '.required' => '"عدد الصفحات" ليوم غد غير مدخل',
            'mistake.' . 0 . '.required' => '"الخطأ" لهذا اليوم غير مدخل',
            'alert.' . 0 . '.required' => '"التنبيه" لهذا اليوم غير مدخل',
            'listener_name.' . 0 . '.required' => '"اسم المستمع" لهذا اليوم غير مدخل',
            'lesson_grade.' . 0 . '.required' => '"درجة الدرس" لهذا اليوم غير مدخلة',
            'lesson_grade.' . 0 . '.numeric' => '"درجة الدرس" لهذا اليوم يجب أن تكون رقمية',
            'last_5_pages_grade.' . 0 . '.required' => '"درجة أخر 5 صفحات" لهذا اليوم غير مدخلة',
            'last_5_pages_grade.' . 0 . '.numeric' => '"درجة أخر 5 صفحات" لهذا اليوم يجب أن تكون رقمية',
            'daily_revision_grade.' . 0 . '.required' => '"درجة المراجعة اليومية" لهذا اليوم غير مدخلة',
            'daily_revision_grade.' . 0 . '.numeric' => '"درجة المراجعة اليومية" لهذا اليوم يجب أن تكون رقمية',
            'behavior_grade.' . 0 . '.required' => '"درجة السلوك" لهذا اليوم غير مدخلة',
            'behavior_grade.' . 0 . '.numeric' => '"درجة السلوك" لهذا اليوم يجب أن تكون رقمية',
        ]);

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
//            ->where('last_5_pages_grade', '!=', '')
            ->whereNotNull('behavior_grade')
//            ->where('behavior_grade', '!=', '')
            ->whereNotNull('daily_revision_grade')
//            ->where('daily_revision_grade', '!=', '')
//            ->whereNotNull('listener_name')
//            ->where('listener_name', '!=', '')
//            ->whereNotNull('mistake')
//            ->where('mistake', '!=', '')
//            ->whereNotNull('alert')
//            ->where('alert', '!=', '')
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
            return redirect()->route('teachers.report.table', $request->student_id);
        }

        $mail_status = $this->sendReport($grades_report, $lessons_report);

        if($mail_status){
            session()->flash('success', 'تم ارسال التقرير اليومي بنجاح');
        }else{
            session()->flash('error', 'فشلت عملية ارسال التقرير!');
        }
        if(request()->date_filter) {
            return redirect()->route('teachers.report.table', $request->student_id . '?date_filter=' . request()->date_filter);
        }
        return redirect()->route('teachers.report.table', $request->student_id);
    }

    public function sendReport($grades = null, $assignment){

        if(is_null($grades)){
//            $student = User::where('id', '=', $assignment->student_id)->first();
            $student = User::with('monthlyScores')->where('users.id', '=', $grades->student_id)->first();
            $to_mails = [];

            if(filter_var($student->father_mail, FILTER_VALIDATE_EMAIL)){
                $to_mails[] = $student->father_mail;
            }

            if(filter_var($student->mother_mail, FILTER_VALIDATE_EMAIL) && !in_array($student->mother_mail, $to_mails)){
                $to_mails[] = $student->mother_mail;
            }

            $nameTitle = $student->section == 'male' ? '' : 'ة';

            $details = [
                'title' => 'التقرير اليومي للطالب' . $nameTitle . ' ' . $student->name . ' - ' . $student->student_number . ' Daily Report',
                'subject' => 'التقرير اليومي للطالب' . $nameTitle . ' ' . $student->name . ' - ' . $student->student_number . ' Daily Report',
                'grades' => $grades,
                'assignment' => $assignment,
                'student_info' => $student,
            ];
            Mail::to($to_mails)
//                ->bcc(['lmsfurqan1@gmail.com'])
                ->send(new ReportMail($details));

            if(empty(Mail::failures())) {
                $report = Report::where('id', '=', $grades->id)->first();
                $report->update(['mail_status' => 1]);
                return 1;
            }

            return 0;
        }else{
            $student = User::with('monthlyScores')->where('users.id', '=', $grades->student_id)->first();
            $to_mails = [];

            if(filter_var($student->father_mail, FILTER_VALIDATE_EMAIL)){
                $to_mails[] = $student->father_mail;
            }

            if(filter_var($student->mother_mail, FILTER_VALIDATE_EMAIL) && !in_array($student->mother_mail, $to_mails)){
                $to_mails[] = $student->mother_mail;
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

//        $currentMonth = \Carbon\Carbon::create()->month()->subMonth()->format('m');
        $currentMonth = date('m');
        $now = Carbon::now();

        $monthly_report_statistics = Report::query()
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->where('student_id', '=', $assignment->student_id);

        $monthly_report = $monthly_report_statistics->get();

        $pdf = PDF::loadView('teachers.emails.monthly_report',
            [
                'student_id' => $assignment->student_id,
                'month' => $currentMonth,
                'monthly_report' => $monthly_report,
                'monthly_report_statistics' => $monthly_report_statistics,
                'now' => $now,
                'user_student' => $student,
            ], [], [
                'format' => 'A2'
            ]);

        Mail::to($to_mails)
//            ->bcc(['lmsfurqan1@gmail.com'])
            ->send(new ReportMail($details, $pdf));

        if(empty(Mail::failures())) {
            $report = Report::where('id', '=', $grades->id)->first();
            $report->update(['mail_status' => 1]);
            return 1;
        }

        return 0;
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

    public static function checkReportNotSent()
    {
        $ids= Report::where("mail_status","<>",1)->orderBy("id","desc")->limit(10)->get('class_number');
        $calses = ClassesTeachers::whereIn("class_number",$ids)->get();
        foreach ($calses as $class) {
            $classids=  Report::where("class_number",$class->class_number)->get('student_id');
            $classids=  user::whereIn('student_number',$classids)->pluck('student_number')->toArray();
            TeacherNotify::dispatch(["email" =>$class->teacher_email,"ids"=>$classids]);
        }
    }

    public static function notifyTeacherFildReport($class)
    {
        $ids = implode(',',$class["ids"]);
        $teacher=Teacher::where("email",$class["email"])->first();
        Notification::send($teacher, new TecherReport($ids));
        Log::alert('sssss' . $ids);
    }

}
