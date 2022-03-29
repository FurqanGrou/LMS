<?php

namespace App\Http\Controllers\Teachers;

use App\AttendanceAbsenceRequests;
use App\Chapter;
use App\Classes;
use App\ClassesTeachers;
use App\Form;
use App\Http\Controllers\Controller;
use App\Mail\MeetingMail;
use App\Meeting;
use App\Service;
use App\SuggestComplaintBox;
use App\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RequestServiceController extends Controller
{
    public function createExamRequest(Form $form)
    {
        $classes_numbers = ClassesTeachers::query()
            ->where('teacher_email', '=', auth()->guard('teacher_web')->user()->email)
            ->get()
            ->pluck('class_number')
            ->toArray(); // get classes numbers of auth teacher

        $student = User::query()->whereIn('class_number', $classes_numbers)->get(); // students
        $teachers = Teacher::query()->get();

        // dd(DB::table("users")->select('id','name')->get());


        return view('teachers.request_services.create', ['students' => $student, 'teachers' => $teachers, 'form' => $form]);
    }

    public function exam()
    {

        $students = User::all();
        $teachers = Teacher::all();
        $chapters = Chapter::all();

        return view('teachers.request_services.exams', ['students' => $students, 'teachers' => $teachers, 'chapters' => $chapters]);
    }
    public function store(Request $request)
    {

        unset($request['_token']);

        $form_id = Form::query()->where('title', '=', $request->form_title)->first()->id ?? null;
        if (is_null($form_id)) {
            session()->flash('error', 'فشلت عملية تقديم الطلب');
            return redirect()->back();
        }

        $uniqid = uniqid();
        foreach ($request->all() as $key => $value) {
            Service::setValue($key, $value, $form_id, $uniqid);
        }

        // write here some insert data logic
        //        Service::setValue('', '', $form_id);

        session()->flash('success', 'تم تقديم الطلب بنجاح');
        return redirect()->back();
    }
    public function showExam(Request $request)
    {

        $request_type = Service::query()->where('request_code', '=', $request->service)->with('form')->first();
        $request_type = $request_type->form->title;

        $values = Service::query()->where('request_code', '=', $request->service)->pluck('value', 'name')->toArray();

        return view('teachers.request_services.exams.show', ['values' => $values, 'request_type' => $request_type, 'request_code' => $request->service]);
    }

    public function createMeeting()
    {

        $teachers = DB::table('classes_teachers')
                    ->where('teacher_email', '=', auth('teacher_web')->user()->email)
                    ->pluck('class_number')
                    ->unique()
                    ->toArray();

        $teachers = ClassesTeachers::query()->whereIn('class_number', $teachers)->where('role', '=', 'supervisor')->pluck('teacher_email')->toArray();
        $teachers = Teacher::query()->whereIn('email', $teachers)->get();

        return view('teachers.request_services.meetings.create', ['teachers' => $teachers]);
    }

    public function storeMeeting(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'status' => 'required|string',
            'favorite_time' => 'required|string',
            'reason' => 'required|string',
        ], [
            'teacher_id.required' => 'يجب عليك التأكد من إختيار اسم معلم',
            'teacher_id.exists' => 'يجب عليك التأكد من إختيار اسم معلم',
            'status.required' => 'يجب عليك التأكد من إختيار حالة الاجتماع',
            'status.string' => 'يجب عليك التأكد من إختيار حالة الاجتماع',
            'favorite_time.required' => 'يجب عليك التأكد من إدخال الوقت المناسب للاجتماع',
            'favorite_time.string' => 'يجب عليك التأكد من إدخال الوقت المناسب للاجتماع',
            'reason.required' => 'يجب عليك التأكد من إدخال سبب الاجتماع',
            'reason.string' => 'يجب عليك التأكد من إدخال سبب الاجتماع',
        ]);

        $result = Meeting::query()->create([
            'teacher_id' => $request->teacher_id,
            'status' => $request->status,
            'favorite_time' => $request->favorite_time,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        $to_emails = [$result->teacher->email, 'e-supervision@furqancenter.com'];
        Mail::to($to_emails)
            ->bcc(['lmsfurqan1@gmail.com'])
            ->send(new MeetingMail($result));

        if(!empty(Mail::failures())) {
            return back()->withError('فشلت عملية ارسال الطلب');
        }

        return back()->withSuccess('تم تقديم طلب الاجتماع بنجاح');
    }

    public function createMeetingWithAdmin()
    {

        $teachers = DB::table('classes_teachers')
                    ->where('teacher_email', '=', auth('teacher_web')->user()->email)
                    ->pluck('class_number')
                    ->unique()
                    ->toArray();

        $teachers = ClassesTeachers::query()->whereIn('class_number', $teachers)->where('role', '=', 'supervisor')->pluck('teacher_email')->toArray();
        $teachers = Teacher::query()->whereIn('email', $teachers)->get();

        return view('teachers.request_services.meetings.create', ['teachers' => $teachers]);
    }

    public function storeMeetingWithAdmin(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'status' => 'required|string',
            'favorite_time' => 'required|string',
            'reason' => 'required|string',
        ], [
            'teacher_id.required' => 'يجب عليك التأكد من إختيار اسم معلم',
            'teacher_id.exists' => 'يجب عليك التأكد من إختيار اسم معلم',
            'status.required' => 'يجب عليك التأكد من إختيار حالة الاجتماع',
            'status.string' => 'يجب عليك التأكد من إختيار حالة الاجتماع',
            'favorite_time.required' => 'يجب عليك التأكد من إدخال الوقت المناسب للاجتماع',
            'favorite_time.string' => 'يجب عليك التأكد من إدخال الوقت المناسب للاجتماع',
            'reason.required' => 'يجب عليك التأكد من إدخال سبب الاجتماع',
            'reason.string' => 'يجب عليك التأكد من إدخال سبب الاجتماع',
        ]);

        $result = Meeting::query()->create([
            'teacher_id' => $request->teacher_id,
            'status' => $request->status,
            'favorite_time' => $request->favorite_time,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        $to_emails = [$result->teacher->email, 'e-supervision@furqancenter.com'];
        Mail::to($to_emails)
            ->bcc(['lmsfurqan1@gmail.com'])
            ->send(new MeetingMail($result));

        if(!empty(Mail::failures())) {
            return back()->withError('فشلت عملية ارسال الطلب');
        }

        return back()->withSuccess('تم تقديم طلب الاجتماع بنجاح');
    }

    public function attendanceAbsenceTeachers()
    {
        $class_numbers = ClassesTeachers::query()->select(['class_number'])->where('teacher_email', '=', auth('teacher_web')->user()->email)->pluck('class_number')->toArray();
        $classes = Classes::query()->select(['id', 'class_number', 'title'])->whereIn('class_number', $class_numbers)->get();

        return view('teachers.request_services.attendanceAbsence', ['classes' => $classes]);
    }

    public function attendanceAbsenceTeachersStore(Request $request)
    {

        if (!$request->type){
            return redirect()->back()->withErrors('يجب عليك التأكد من صحة نوع الطلب');
        }

        if ($request->type == 'absence' && $request->reason_excuse == 'null'){
            return redirect()->back()->withErrors('يجب عليك التأكد من صحة سبب الغياب');
        }

        $request->validate([
            'date_excuse' => 'required|date',
            'reason_excuse' => 'required|string',
            'absence_reason' => ($request->reason_excuse == 'other' && $request->type == 'absence') ? 'required|string' : 'nullable',
            'duration_delay' => ($request->type == 'delay') ? 'required' : 'nullable',
            'exit_time' => ($request->type == 'exit') ? 'required' : 'nullable',
            'class_numbers' => 'required',
        ], [
            'date_excuse.required' => 'يجب عليك التأكد من إدخال تاريخ العذر',
            'date_excuse.date'     => 'يجب عليك التأكد من صحة تاريخ العذر',
            'reason_excuse.required'  => 'يجب عليك التأكد من إدخال العذر/السبب',
            'absence_reason.required' => 'يجب عليك التأكد من إدخال العذر/السبب',
            'duration_delay.required' => 'يجب عليك التأكد من إدخال مدة التأخير',
            'exit_time.required'      => 'يجب عليك التأكد من إدخال موعد الخروج',
            'class_numbers.required'      => 'يجب عليك التأكد من اختيار حلقة على الأقل',
        ]);

        foreach ($request->class_numbers as $class_number){
            AttendanceAbsenceRequests::query()->create([
                'request_type' => $request->type,
                'date_excuse' => $request->date_excuse,
                'reason_excuse' => ($request->reason_excuse == 'other' && $request->type == 'absence') ? $request->absence_reason : $request->reason_excuse,
                'additional_attachments_path' => ($request->type == 'absence' && $request->absence_additional_attachments) ? $request->file('absence_additional_attachments')->store('/absence_additional_attachments') : null,
                'duration_delay' => $request->duration_delay,
                'exit_time' => $request->exit_time,
                'teacher_id' => auth('teacher_web')->user()->id,
                'class_number' => $class_number,
            ]);
        }

        return redirect()->back()->withSuccess('تم تقديم طلبك بنجاح');
    }

//    public function checkPeriod(Request $request)
//    {
//        $date = Carbon::today()->toDate()->format('Y-m-d');
//        $classes = Classes::query()->select(['period', 'title', 'class_number'])->whereIn('class_number', $request->class_numbers)->get();
//
//        foreach ($classes as $class){
//            if (getPeriodTimeAvailable($class->period)){
//                continue;
//            }elseif($request->date == $date){
//
//            }else{
//                return response()->json(['status' => getPeriodTimeAvailable($class->period), 'class_number' => $class->class_number]);
//            }
//        }
//
//        return response()->json(['status' => true]);
//    }

    public function showAppliedRequests()
    {
        $appliedRequests = AttendanceAbsenceRequests::query()->where('teacher_id', auth('teacher_web')->user()->id)->orderByDesc('id')->paginate(15);

        return view('teachers.request_services.show_attendanceAbsence', ['appliedRequests' => $appliedRequests]);
    }

    public function showSingleAppliedRequest(AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        return view('teachers.request_services.show_single_attendanceAbsence', ['attendanceAbsenceRequest' => $attendanceAbsenceRequests]);
    }

    public function editAttendanceAbsence(AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        $class_numbers = ClassesTeachers::query()->select(['class_number'])->where('teacher_email', '=', auth('teacher_web')->user()->email)->pluck('class_number')->toArray();
        $classes = Classes::query()->select(['id', 'class_number', 'title'])->whereIn('class_number', $class_numbers)->get();

        return view('teachers.request_services.edit_attendanceAbsence', ['attendanceAbsenceRequest' => $attendanceAbsenceRequests, 'classes' => $classes]);
    }
}
