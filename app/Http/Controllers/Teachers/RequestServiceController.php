<?php

namespace App\Http\Controllers\Teachers;

use App\Chapter;
use App\ClassesTeachers;
use App\Form;
use App\Http\Controllers\Controller;
use App\Mail\MeetingMail;
use App\Meeting;
use App\Service;
use App\SuggestComplaintBox;
use App\Teacher;
use App\User;
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
            'favorite_time.required' => 'يجب عليك التأكد من إدخال الوقت المناسب للأجتماع',
            'favorite_time.string' => 'يجب عليك التأكد من إدخال الوقت المناسب للأجتماع',
            'reason.required' => 'يجب عليك التأكد من إدخال سبب الأجتماع',
            'reason.string' => 'يجب عليك التأكد من إدخال سبب الأجتماع',
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
            'favorite_time.required' => 'يجب عليك التأكد من إدخال الوقت المناسب للأجتماع',
            'favorite_time.string' => 'يجب عليك التأكد من إدخال الوقت المناسب للأجتماع',
            'reason.required' => 'يجب عليك التأكد من إدخال سبب الأجتماع',
            'reason.string' => 'يجب عليك التأكد من إدخال سبب الأجتماع',
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

}
