<?php

namespace App\Http\Controllers\Teachers;
use App\Chapter;
use App\Classes;
use App\ClassesTeachers;
use App\Http\Controllers\Controller;
use App\Jobs\TeacherNotify;
use App\Lesson;
use App\Mail\ReportMail;
use App\NoteParent;
use App\Notifications\TecherReport;
use App\Report;
use App\Teacher;
use App\User;
use App\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $today_date = Carbon::today();
        $tomorrow_date = Carbon::tomorrow();
        $tomorrow_date_check = Carbon::tomorrow();

        $user = User::where('id', '=', $id)->first();

        $notes = NoteParent::where('gender', $user->section)->latest()->get();

        $currentMonth = date('m');
        $monthReports = Report::whereRaw('MONTH(created_at) = ?', [$currentMonth])->where('student_id', '=', $id)->whereNotNull('new_lesson')->whereNotNull('lesson_grade')->get();

//        if(str_contains($today_date->format('l') ,'Friday')) {
//
//            return view('teachers.reports.create_has_report', ['hasReportYesterday' => hasReportYesterday, 'notes' => $notes, 'lastGrades' => $lastGrades, 'monthReports' => $monthReports, 'user' => $user]);
//        }

        if(str_contains($tomorrow_date->format('l') ,'Friday')){
            $tomorrow_date->addDays(2);
            $tomorrow_date_check = $tomorrow_date_check->addDays(2);
        }
        $tomorrow_date = Carbon::createFromDate($tomorrow_date->year, $tomorrow_date->month, $tomorrow_date->day)->format('l d-m-Y');

        // check if this user is not has any report (new student)
        $is_new_student = Report::where('student_id', '=', $id)->whereNull('lesson_grade')->latest()->first();
        $is_new_student = empty($is_new_student);

        if($is_new_student){
            return view('teachers.reports.create_new_student', ['is_new_student' => $is_new_student, 'notes' => $notes, 'tomorrow_date' => $tomorrow_date, 'user' => $user]);
        }
        //end chek

        // check if this user is has any report for tomorrow or not
        $hasReportTomorrow = Report::where('student_id', '=', $id)->whereNull('lesson_grade')->whereDate('created_at', '=', $tomorrow_date_check)->latest()->first();

        $is_friday = str_contains($today_date->format('l') ,'Friday');
        if(!empty($hasReportTomorrow) || $is_friday){

            if($is_friday){
                $tomorrow_date_check = $today_date->addDays(2);
                $hasReportTomorrow = Report::where('student_id', '=', $id)->whereNull('lesson_grade')->whereDate('created_at', '=', $tomorrow_date_check)->latest()->first();
            }

            $lastGrades = Report::where('student_id', '=', $id)->whereNotNull('lesson_grade')->whereDate('created_at', '<', $tomorrow_date_check)->latest()->first();

            if(!User::where('id', '=', $lastGrades->student_id)->first()->class_number){
                session()->flash('success', 'تمت حفظ البيانات ونقل الطالب !!');
                return redirect(route('teachers.teacher.index'));
            }

            return view('teachers.reports.create_has_report', ['hasReportTomorrow' => $hasReportTomorrow, 'notes' => $notes, 'lastGrades' => $lastGrades, 'monthReports' => $monthReports, 'user' => $user]);
        }
        //end chek

        $user_report = Report::where('student_id', '=', $id)->whereNull('lesson_grade')->latest()->first();

        return view('teachers.reports.create', ['user' => $user, 'tomorrow_date' => $tomorrow_date, 'notes' => $notes, 'user_report' => $user_report, 'monthReports' => $monthReports]);
    }

    public function getReportAbsence(Request $request)
    {
        $report = Report::where('student_id', '=', $request->student_id)->whereNotNull('new_lesson')->latest()->first();
        return response()->json(['report' => $report], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request['lesson_grade'] = $request['lesson_grade'] ?? 0;

        $tomorrow = Carbon::tomorrow();

        if(str_contains($tomorrow->format('l') ,'Friday')){
            $tomorrow->addDays(2);
        }

        $tomorrow_created_at = Carbon::createFromDate($tomorrow->year, $tomorrow->month, $tomorrow->day)->format('Y-m-d H:i:s');
        $request['created_at'] = $tomorrow_created_at;

        $rules = [
            'date'       => 'required',
            'class_number' => 'required',
            'student_id' => 'required',
            'created_at' => 'required',
            'new_lesson' => 'nullable|string',
            'new_lesson_from'=> 'nullable',
            'new_lesson_to'  => 'nullable',
            'last_5_pages'   => 'nullable',
            'daily_revision' => 'nullable',
            'daily_revision_from' => 'nullable',
            'daily_revision_to'   => 'nullable',
            'number_pages'   => 'nullable',
        ];
        if(!$request->has('absence')) {
            $rules = [
                'date'       => 'required',
                'class_number' => 'required',
                'student_id' => 'required',
                'created_at' => 'required',
                'new_lesson' => 'nullable|string',
                'new_lesson_from'=> 'nullable',
                'new_lesson_to'  => 'nullable',
                'last_5_pages'   => 'nullable',
                'daily_revision' => 'nullable',
                'daily_revision_from' => 'nullable',
                'daily_revision_to'   => 'nullable',
                'number_pages'   => 'nullable',
                'lesson_grade' => 'nullable|numeric',
                'last_5_pages_grade' => 'nullable|numeric',
                'daily_revision_grade' => 'nullable|numeric',
                'behavior_grade' => 'nullable|numeric',
                'alert' => 'nullable|numeric',
                'mistake' => 'nullable|numeric',
                'listener_name' => 'nullable|string',
                'notes_to_parent' => 'nullable|string',
            ];
        }

        $messages = [
            'date.required'       => 'يجب إدخال التاريخ',
            'class_number.required'       => 'يجب إدخال رقم الحلقة',
            'student_id.required'       => 'يجب إدخال رقم الطالب',
            'created_at.required'       => 'يجب إدخال تاريخ الادخال',
            'new_lesson.string'       => 'يجب إدخال قيمة صحيحة في الدرس الجديد',
            'lesson_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة الدرس',
            'last_5_pages_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة اخر 5 صفحات',
            'daily_revision_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة المراجعة اليومية',
            'behavior_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة السلوك',
            'alert.numeric' => 'يجب إدخال قيمة رقمية في التنبيه',
            'mistake.numeric' => 'يجب إدخال قيمة رقمية في الاخطاء',
            'listener_name.string' => 'يجب إدخال قيمة صحيحة للمستمع',
            'notes_to_parent.string' => 'يجب إدخال قيمة صحيحة لملاحظات الوالدين',
        ];
        $report = $request->validate($rules, $messages);
        $today_date_report = Carbon::today();
        $today_created_at = Carbon::createFromDate($today_date_report->year, $today_date_report->month, $today_date_report->day)->format('Y-m-d H:i:s');
        $today_day = Carbon::createFromDate($today_date_report->year, $today_date_report->month, $today_date_report->day)->format('l d-m-Y');

        $absence_grade = is_null($request->absence) ? 0 : $request->absence;

        $total = ($request->behavior_grade + $request->daily_revision_grade + $request->last_5_pages_grade + $request->lesson_grade);

        $report_grades = Report::updateOrCreate(
            ['student_id' => $request->student_id,
                'created_at' => Report::where('created_at', 'LIKE', $today_date_report->format('Y-m-d') . ' %')->first()->created_at ?? null
            ],
            [
                'date' => $today_day,
                'lesson_grade' => $request->lesson_grade,
                'mistake' => $request->mistake,
                'alert' => $request->alert,
                'listener_name' => $request->listener_name,
                'last_5_pages_grade' => $request->last_5_pages_grade,
                'daily_revision_grade' => $request->daily_revision_grade,
                'behavior_grade' => $request->behavior_grade,
                'notes_to_parent' => $request->notes_to_parent,
                'absence' => $absence_grade,
                'total' => $total,
                'class_number' => $request->class_number,
                'created_at' => $today_created_at,
            ]
        );

        $newReport = Report::create([
            'date'       => $request->date,
            'class_number'       => $request->class_number,
            'student_id'       => $request->student_id,
            'created_at'       => $request->created_at,
            'new_lesson'       => $request->new_lesson,
            'new_lesson_from'=> $request->new_lesson_from,
            'new_lesson_to'  => $request->new_lesson_to,
            'last_5_pages'   => $request->last_5_pages,
            'daily_revision' => $request->daily_revision,
            'daily_revision_from' => $request->daily_revision_from,
            'daily_revision_to'   => $request->daily_revision_to,
            'number_pages'   => $request->number_pages,
        ]);

//        $this->sendReport($report_grades, $newReport);

        session()->flash('success', 'تمت اضافة التقرير بنجاح');

        return redirect(route('teachers.report.create', $request->student_id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $report = Report::find($id);
        $is_new_student = Report::where('student_id', '=', $report->student_id)->whereNotNull('lesson_grade')->first();
        $is_new_student = !empty($is_new_student);

        $notes = NoteParent::where('gender', User::where('id', '=', $report->student_id)->first()->section)->latest()->get();
        $user = User::where('id', '=', $report->student_id)->first();

        return view('teachers.reports.edit', ['report' => $report, 'is_new_student' => $is_new_student, 'notes' => $notes, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

//        dd($request->all());
        if($request->updateType == 'normal') {
            DB::beginTransaction();
            try {

                $user_report = Report::where('id', '=', $id)->first();

                if(!is_null($user_report->lesson_grade)){
                    $rules = [
                        'lesson_grade' => 'nullable|numeric',
                        'last_5_pages_grade' => 'nullable|numeric',
                        'daily_revision_grade' => 'nullable|numeric',
                        'behavior_grade' => 'nullable|numeric',
                        'alert' => 'nullable|numeric',
                        'mistake' => 'nullable|numeric',
                        'notes_to_parent' => 'nullable|string',
                        'listener_name' => 'nullable|string',
                    ];

                    $messages = [
                        'new_lesson.string'       => 'يجب إدخال قيمة صحيحة في الدرس الجديد',
                        'lesson_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة الدرس',
                        'last_5_pages_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة اخر 5 صفحات',
                        'daily_revision_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة المراجعة اليومية',
                        'behavior_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة السلوك',
                        'alert.numeric' => 'يجب إدخال قيمة رقمية في التنبيه',
                        'mistake.numeric' => 'يجب إدخال قيمة رقمية في الاخطاء',
                        'listener_name.string' => 'يجب إدخال قيمة صحيحة للمستمع',
                        'notes_to_parent.string' => 'يجب إدخال قيمة صحيحة لملاحظات الوالدين',
                    ];

                    $request->validate($rules, $messages);

                    $request['lesson_grade'] = $request['lesson_grade'] ?? 0;

                    $total = ($request->behavior_grade + $request->daily_revision_grade + $request->last_5_pages_grade + $request->lesson_grade);
                    $user_report->update([
                        'lesson_grade' => $request->lesson_grade,
                        'mistake' => $request->mistake,
                        'alert' => $request->alert,
                        'listener_name' => $request->listener_name,
                        'last_5_pages_grade' => $request->last_5_pages_grade,
                        'daily_revision_grade' => $request->daily_revision_grade,
                        'behavior_grade' => $request->behavior_grade,
                        'total' => $total,
                        'notes_to_parent' => $request->notes_to_parent,
                    ]);
                }

                $rules = [
                    'new_lesson' => 'nullable|string',
                    'new_lesson_from' => 'nullable',
                    'new_lesson_to' => 'nullable',
                    'last_5_pages' => 'nullable',
                    'daily_revision' => 'nullable',
                    'daily_revision_from' => 'nullable',
                    'daily_revision_to' => 'nullable',
                    'number_pages' => 'nullable',
                ];

                $request->validate($rules);

                $oldReport = Report::find($id);
                $oldReport->update([
                    'new_lesson' => $request->new_lesson,
                    'new_lesson_from' => $request->new_lesson_from,
                    'new_lesson_to' => $request->new_lesson_to,
                    'last_5_pages' => $request->last_5_pages,
                    'daily_revision' => $request->daily_revision,
                    'daily_revision_from' => $request->daily_revision_from,
                    'daily_revision_to' => $request->daily_revision_to,
                    'number_pages' => $request->number_pages,
                ]);
                session()->flash('success', 'تمت تحديث البيانات بنجاح');
                DB::commit();
//                $this->sendReport($user_report, $oldReport);
                return redirect(route('teachers.report.edit', $id));
            }catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }else {

            DB::beginTransaction();
            try {

                $user_report = Report::where('id', '=', $id)->latest()->first();

                if($request->has('absence')){
                    $user_report->update([
                        'lesson_grade' => 'غ',
                        'mistake' => ' ',
                        'alert' => ' ',
                        'listener_name' => ' ',
                        'last_5_pages_grade' => ' ',
                        'daily_revision_grade' => ' ',
                        'behavior_grade' => ' ',
                        'total' => 0,
                        'notes_to_parent' => 'الطالب غائب',
                        'absence' => $request->absence,
                    ]);
                }else{
                    $rules = [
                        'lesson_grade' => 'nullable|numeric',
                        'last_5_pages_grade' => 'nullable|numeric',
                        'daily_revision_grade' => 'nullable|numeric',
                        'behavior_grade' => 'nullable|numeric',
                        'alert' => 'nullable|numeric',
                        'mistake' => 'nullable|numeric',
                        'listener_name' => 'nullable|string',
                        'notes_to_parent' => 'nullable|string',
                    ];

                    $messages = [
                        'lesson_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة الدرس',
                        'last_5_pages_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة اخر 5 صفحات',
                        'daily_revision_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة المراجعة اليومية',
                        'behavior_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة السلوك',
                        'alert.numeric' => 'يجب إدخال قيمة رقمية في التنبيه',
                        'mistake.numeric' => 'يجب إدخال قيمة رقمية في الاخطاء',
                        'listener_name.string' => 'يجب إدخال قيمة صحيحة للمستمع',
                        'notes_to_parent.string' => 'يجب إدخال قيمة صحيحة لملاحظات الوالدين',
                    ];

                    $request->validate($rules, $messages);

                    $total = ($request->behavior_grade + $request->daily_revision_grade + $request->last_5_pages_grade + $request->lesson_grade);

                    $user_report->update([
                        'lesson_grade' => $request->lesson_grade ?? 0,
                        'mistake' => $request->mistake,
                        'alert' => $request->alert,
                        'last_5_pages_grade' => $request->last_5_pages_grade,
                        'daily_revision_grade' => $request->daily_revision_grade,
                        'behavior_grade' => $request->behavior_grade,
                        'total' => $total,
                        'listener_name' => $request->listener_name,
                        'notes_to_parent' => $request->notes_to_parent,
                    ]);

                }

                $tomorrow_date = Carbon::tomorrow();
                $tomorrow_created_at = Carbon::createFromDate($tomorrow_date->year, $tomorrow_date->month, $tomorrow_date->day)->format('Y-m-d H:i:s');
                if(str_contains($tomorrow_date->format('l') ,'Friday')){
                    $tomorrow_created_at = $tomorrow_date->addDays(2);
                }

                $request['created_at'] = $tomorrow_created_at;
                $rules = [
                    'date' => 'required',
                    'class_number' => 'required',
                    'student_id' => 'required',
                    'created_at' => 'required',
                    'new_lesson' => 'nullable',
                    'new_lesson_from' => 'nullable',
                    'new_lesson_to' => 'nullable',
                    'last_5_pages' => 'nullable',
                    'daily_revision' => 'nullable',
                    'daily_revision_from' => 'nullable',
                    'daily_revision_to' => 'nullable',
                    'number_pages' => 'nullable',
                ];

                $report = $request->validate($rules);

                $newReport = Report::create($report);

//                $this->sendReport($user_report, $newReport);

                session()->flash('success', 'تم حفظ البيانات بنجاح');

                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return redirect(route('teachers.report.create', $request->student_id));
    }

    public function updateTomorrow(Request $request, $id)
    {

        $request['lesson_grade'] = $request['lesson_grade'] ?? 0;

        if($request->report_id != 'no' && !$request->has('absence')){
            $grades_report = Report::where('id', '=', $request->report_id)->latest()->first();

            $rules = [
                'lesson_grade' => 'nullable|numeric',
                'last_5_pages_grade' => 'nullable|numeric',
                'daily_revision_grade' => 'nullable|numeric',
                'behavior_grade' => 'nullable|numeric',
                'alert' => 'nullable|numeric',
                'mistake' => 'nullable|numeric',
                'listener_name' => 'nullable|string',
                'notes_to_parent' => 'nullable|string',
            ];

            $messages = [
                'new_lesson.string'       => 'يجب إدخال قيمة صحيحة في الدرس الجديد',
                'lesson_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة الدرس',
                'last_5_pages_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة اخر 5 صفحات',
                'daily_revision_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة المراجعة اليومية',
                'behavior_grade.numeric' => 'يجب إدخال قيمة رقمية في درجة السلوك',
                'alert.numeric' => 'يجب إدخال قيمة رقمية في التنبيه',
                'mistake.numeric' => 'يجب إدخال قيمة رقمية في الاخطاء',
                'listener_name.string' => 'يجب إدخال قيمة صحيحة للمستمع',
                'notes_to_parent.string' => 'يجب إدخال قيمة صحيحة لملاحظات الوالدين',
            ];

            $request->validate($rules, $messages);

            $total = ($request->behavior_grade + $request->daily_revision_grade + $request->last_5_pages_grade + $request->lesson_grade);

            $grades_report->update([
                'lesson_grade' => $request->lesson_grade,
                'mistake' => $request->mistake,
                'alert' => $request->alert,
                'listener_name' => $request->listener_name,
                'last_5_pages_grade' => $request->last_5_pages_grade,
                'daily_revision_grade' => $request->daily_revision_grade,
                'behavior_grade' => $request->behavior_grade,
                'total' => $total,
                'notes_to_parent' => $request->notes_to_parent,
                'absence' => 0,
            ]);
        }

        if($request->report_id != 'no' && $request->has('absence')){
            $grades_report = Report::where('id', '=', $request->report_id)->latest()->first();
            $grades_report->update([
                'lesson_grade' => 'غ',
                'mistake' => ' ',
                'alert' => ' ',
                'listener_name' => ' ',
                'last_5_pages_grade' => ' ',
                'daily_revision_grade' => ' ',
                'behavior_grade' => ' ',
                'total' => 0,
                'notes_to_parent' => 'الطالب غائب',
                'absence' => $request->absence,
            ]);

            $request['lesson_grade'] = 'غ';
            $request['mistake'] = ' ';
            $request['alert'] = ' ';
            $request['listener_name'] = ' ';
            $request['last_5_pages_grade'] = ' ';
            $request['daily_revision_grade'] = ' ';
            $request['behavior_grade'] = ' ';
            $request['notes_to_parent'] = 'الطالب غائب';
        }

        $user_report = Report::where('id', '=', $id)->latest()->first();

        $rules = [
            'new_lesson' => 'nullable|string',
            'new_lesson_from'=> 'nullable',
            'new_lesson_to'  => 'nullable',
            'last_5_pages'   => 'nullable',
            'daily_revision' => 'nullable',
            'daily_revision_from' => 'nullable',
            'daily_revision_to'   => 'nullable',
            'number_pages'   => 'nullable',
        ];

        $request->validate($rules);

        $user_report->update([
            'new_lesson' => $request->new_lesson,
            'new_lesson_from' => $request->new_lesson_from,
            'new_lesson_to' => $request->new_lesson_to,
            'last_5_pages' => $request->last_5_pages,
            'daily_revision' => $request->daily_revision,
            'daily_revision_from' => $request->daily_revision_from,
            'daily_revision_to' => $request->daily_revision_to,
            'number_pages' => $request->number_pages,
        ]);

        if(isset($request["update_and_send"]) && (!isset($request->lesson_grade) || !isset($request->last_5_pages_grade) || !isset($request->behavior_grade) || !isset($request->daily_revision_grade) || !isset($request->daily_revision) || !isset($request->daily_revision_from) || !isset($request->daily_revision_to) || !isset($request->new_lesson) || !isset($request->new_lesson_from) || !isset($request->new_lesson_to))){
            session()->flash('error', 'لم يتم ارسال التقرير اليومي يرجى التأكد من إدخال جميع البيانات!!');
            return redirect(route('teachers.report.create', $request->student_id));
        }

        if(isset($request["update_and_send"]) && isset($request->daily_revision) && isset($request->lesson_grade)){
            if(!isset($grades_report)){
                $this->sendReport(null, $user_report);
            }else{
                $this->sendReport($grades_report, $user_report);
            }
            session()->flash('success', 'تم تحديث وارسال التقرير اليومي بنجاح');
        }else{
            session()->flash('success', 'تم تحديث التقرير اليومي بنجاح');
        }

        return redirect(route('teachers.report.create', $request->student_id));
    }

    public function sendReport($grades = null, $assignment){

        if(is_null($grades)){
            $student = User::where('id', '=', $assignment->student_id)->first();
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
                ->bcc(['lmsfurqan1@gmail.com'])
                ->send(new ReportMail($details));

            if(empty(Mail::failures())) {
                $report = Report::where('id', '=', $grades->id)->first();
                $report->update(['mail_status' => 1]);
                return 1;
            }

            return 0;
        }else{
            $student = User::where('id', '=', $grades->student_id)->first();
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

        $currentMonth = date('m');

        $monthly_report_statistics = Report::query()
                                            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
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

//        return $pdf->stream('document.pdf');

        Mail::to($to_mails)
            ->bcc(['lmsfurqan1@gmail.com'])
            ->send(new ReportMail($details, $pdf));

        if(empty(Mail::failures())) {
            $report = Report::where('id', '=', $grades->id)->first();
            $report->update(['mail_status' => 1]);
            return 1;
        }

        return 0;
    }

    public function checkGrade($lesson_grade)
    {
        if (is_numeric($lesson_grade))
            return $lesson_grade;

        return 0;
    }

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
        $new_lessons = Lesson::query()->get();
        $daily_revision = Chapter::query()->get();

        $class_number = User::query()->where('id', '=', request()->student_id)->first()->class_number;
        $students = User::query()->where('class_number', '=', $class_number)->orderBy('student_number', 'ASC')->get();

        return view('teachers.reports.monthly_table', ['now' => $now, 'month' => $month, 'reports' => $reports, 'notes' => $notes, 'students' => $students, 'new_lessons' => $new_lessons, 'daily_revision' => $daily_revision]);
    }

    public function reportTableStore(Request $request)
    {

        $today = Carbon::today();
        $date = Carbon::today()->format('Y-m-d');

        $yesterday = Carbon::createFromDate($today->year, $today->month, $today->day)->format('l d-m-Y');
        if($date != $request->created_at){
            $report = Report::updateOrCreate(
                [
                    'student_id' => $request->student_id,
                    'date'       => $yesterday,
                    'created_at' => $date,
                ],
                [
                    'mail_status' => 0,
                ]
            );
        }

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
                    'class_number' => getStudentDetails(request()->student_id)->class_number,
                    'mail_status' => 0,
                ]
            );
        }

        if($request->type == 'grades'){

            $total = 0;
            if ($request->notes_to_parent == 'الطالب غائب' || $request->notes_to_parent == 'دوام 3 أيام'){
                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date' => $request->date,
                        'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => 'غ',
                        'last_5_pages_grade' => $request->notes_to_parent == 'الطالب غائب' ? 0 : '-',
                        'daily_revision_grade' => $request->notes_to_parent == 'الطالب غائب' ? 0 : '-',
                        'behavior_grade' => $request->notes_to_parent == 'الطالب غائب' ? 0 : '-',
                        'notes_to_parent' => $request->notes_to_parent,
                        'absence' => $request->notes_to_parent == 'الطالب غائب' ? -5 : 0,
                        'total' => $total,
                        'mail_status' => 0,
                        'class_number' => getStudentDetails(request()->student_id)->class_number,
                    ]
                );
            }elseif($request->notes_to_parent == 'نشاط لا صفي'){
                $report = Report::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'date' => $request->date,
                        'created_at' => Report::query()->where('student_id', '=', $request->student_id)->where('created_at', 'LIKE', $request->created_at . ' %')->first()->created_at ?? $request->created_at
                    ],
                    [
                        'lesson_grade' => 1,
                        'last_5_pages_grade' => 2,
                        'daily_revision_grade' => 1,
                        'behavior_grade' => 1,
                        'notes_to_parent' => 'نشاط لا صفي',
                        'absence' => 0,
                        'total' => 5,
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
                        'mail_status' => 0,
                        'class_number' => getStudentDetails(request()->student_id)->class_number,
                    ]
                );
            }

        }

        return response()->json(['report' => $report], 200);
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

    public function sendReportTable(Request $request)
    {
        $notes_to_parents = request()->notes_to_parent[0];

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
            'mistake.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required' ,
            'alert.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required' ,
            'number_pages' => 'required',
            'number_pages.*' => 'required',
            'listener_name.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required|string' ,
            'lesson_grade.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required|numeric' ,
            'last_5_pages_grade.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required' ,
            'daily_revision_grade.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required' ,
            'behavior_grade.' . 0  => $notes_to_parents == 'الطالب غائب' || $notes_to_parents == 'دوام 3 أيام' || $notes_to_parents == 'نشاط لا صفي' ? '' : 'required' ,
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
            return redirect()->route('teachers.report.table', $request->student_id);
        }

        $mail_status = $this->sendReport($grades_report, $lessons_report);

        if($mail_status){
            session()->flash('success', 'تم ارسال التقرير اليومي بنجاح');
        }else{
            session()->flash('success', 'فشلت عملية ارسال التقرير!');
        }
        if(request()->date_filter) {
            return redirect()->route('teachers.report.table', $request->student_id . '?date_filter=' . request()->date_filter);
        }
        return redirect()->route('teachers.report.table', $request->student_id);
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
