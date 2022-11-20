<?php

namespace App\Http\Controllers\Teachers;

use App\AttendanceAbsenceRequests;
use App\Chapter;
use App\Classes;
use App\ClassesTeachers;
use App\Form;
use App\Http\Controllers\Controller;
use App\Mail\AttendanceAbsenceRequestMail;
use App\Mail\MeetingMail;
use App\Meeting;
use App\Notifications\AlertMessageNotification;
use App\Notifications\RequestServiceExcuseNotification;
use App\Service;
use App\SuggestComplaintBox;
use App\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

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

    public function checkPeriod(Request $request)
    {
        if (!$request->type){
            return response()->json(['errors' => ['يجب عليك التأكد من صحة نوع الطلب']], 404);
        }

        if ($request->type == 'absence' && $request->reason_excuse == 'null'){
            return response()->json(['errors' => ['يجب عليك التأكد من صحة سبب الغياب']], 404);
        }

        $validator = Validator::make($request->all(), [
            'class_numbers' => 'required',
            'date_excuse' => 'required|date',
            'reason_excuse' => 'required|string',
            'absence_reason' => ($request->reason_excuse == 'other' && $request->type == 'absence') ? 'required|string' : 'nullable',
            'duration_delay' => ($request->type == 'delay') ? 'required' : 'nullable',
            'exit_time' => ($request->type == 'exit') ? 'required' : 'nullable',
        ], [
            'date.required' => 'يجب عليك اختيار تاريخ صالح',
            'class_numbers.required' => 'يجب عليك اختيار حلقة صحيحة',
            'date_excuse.required' => 'يجب عليك التأكد من إدخال تاريخ العذر',
            'date_excuse.date'     => 'يجب عليك التأكد من صحة تاريخ العذر',
            'reason_excuse.required'  => 'يجب عليك التأكد من إدخال العذر/السبب',
            'absence_reason.required' => 'يجب عليك التأكد من إدخال العذر/السبب',
            'duration_delay.required' => 'يجب عليك التأكد من إدخال مدة التأخير',
            'exit_time.required'      => 'يجب عليك التأكد من إدخال موعد الخروج',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()], 404);
        }

//        $class = Classes::query()->select(['period', 'title', 'class_number'])->where('class_number', '=', $request->class_number)->first();
//        $status = getPeriodTimeAvailable(['period' => $class->period, 'excuse_date' => $request->date_excuse]);
//
//        if(!$status){
//            return response()->json(['status' => $status, 'errors' => ['لا يمكنك طلب الاذن إلا قبل موعد الحلقة بساعتين أو أكثر']], 404);
//        }

        $class_numbers = explode(',', $request->class_numbers);

        $data = [];
        foreach ($class_numbers as $class_number){
            $data[] = [
                'request_type' => $request->type,
                'date_excuse' => $request->date_excuse,
                'reason_excuse' => ($request->reason_excuse == 'other' && $request->type == 'absence') ? $request->absence_reason : $request->reason_excuse,
                'additional_attachments_path' => ($request->type == 'absence' && $request->absence_additional_attachment) ? $request->file('absence_additional_attachment')->store('/absence_additional_attachment') : null,
                'duration_delay' => $request->duration_delay,
                'exit_time' => $request->exit_time,
                'teacher_id' => auth('teacher_web')->user()->id,
                'class_number' => $class_number,
            ];
        }

        DB::table('attendance_absence_requests')->insert($data);

        $subjects = [
            'absence' => 'طلب اذن غياب معلم عن حلقة',
            'delay' => 'طلب اذن تأخير معلم عن حلقة',
            'exit' => 'طلب اذن خروج معلم من حلقة',
        ];

        Mail::raw('ورد لديكم طلب اذن مقدم من المعلم/ة (' . auth('teacher_web')->user()->name . ') عبر لوحة التحكم الخاصة بالاذونات يمكنكم النظر فيه والافادة',
            function ($message) use ($subjects, $request) {
                $message->to('attendance.permissions@furqancenter.com')
//                     ->cc(['attendance.permissions@furqancenter.com'])
                    ->subject($subjects[$request->type]);
            });

//        Notification::route('mail', ['alfurqangroup2020@gmail.com'])->notify(new RequestServiceExcuseNotification($request->all()));

//        Artisan::call('cache:clear');

        return response()->json(['status' => true, 'errors' => []], 200);
    }

    public function showAppliedRequests()
    {
        $appliedRequests = AttendanceAbsenceRequests::query()
            ->where('teacher_id', auth('teacher_web')->user()->id)
            ->orderByDesc('id')
            ->paginate(15);

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

    public function updateAttendanceAbsence(Request $request, AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        $request->validate([
            'type' => 'required',
            'class_number' => 'required|numeric',
            'date_excuse'  => 'required|date',
            'reason_excuse' => 'required|string',
            'absence_reason' => ($request->reason_excuse == 'other' && $request->type == 'absence') ? 'required|string' : 'nullable',
            'duration_delay' => ($request->type == 'delay') ? 'required' : 'nullable',
            'exit_time' => ($request->type == 'exit') ? 'required' : 'nullable',
        ], [
            'class_number.required' => 'يجب عليك اختيار حلقة صحيحة',
            'class_number.numeric' => 'يجب عليك اختيار حلقة صحيحة',
            'date_excuse.required' => 'يجب عليك التأكد من إدخال تاريخ العذر',
            'date_excuse.date'     => 'يجب عليك التأكد من صحة تاريخ العذر',
            'reason_excuse.required'  => 'يجب عليك التأكد من إدخال العذر/السبب',
            'absence_reason.required' => 'يجب عليك التأكد من إدخال العذر/السبب',
            'duration_delay.required' => 'يجب عليك التأكد من إدخال مدة التأخير',
            'exit_time.required'      => 'يجب عليك التأكد من إدخال موعد الخروج',
        ]);

        $attendanceAbsenceRequests->update($request->except(['type']));

        return back()->withSuccess('تم تعديل الطلب بنجاح');
    }

    public function cancelRequest(Request $request, AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        if ($request->status == 'true'){
            $attendanceAbsenceRequests->update([
                'status' => 'canceled',
            ]);

//            Mail::to(['lmsfurqan1@gmail.com'])
//            ->cc([self::$to_mails[$absenceRequests->teacher->section]])
//            ->bcc(self::$bcc)
//            ->send(new AttendanceAbsenceRequestMail(''));

        }else{
            $attendanceAbsenceRequests->update([
                'status' => 'pending',
            ]);
        }

        return response()->json(['status' => true, 'errors' => [], 'data' => $attendanceAbsenceRequests->id], 200);
    }
}
