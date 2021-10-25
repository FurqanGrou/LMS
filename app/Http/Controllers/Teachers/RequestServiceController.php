<?php

namespace App\Http\Controllers\Teachers;

use App\ClassesTeachers;
use App\Form;
use App\Http\Controllers\Controller;
use App\Service;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;

class RequestServiceController extends Controller
{
    public function create()
    {
        $classes_numbers = ClassesTeachers::query()->where('teacher_email', '=', auth()->guard('teacher_web')->user()->email)->get()->pluck('class_number')->toArray();
        $student = User::query()->whereIn('class_number', $classes_numbers)->get();

        $teachers = Teacher::query()->get();

        return view('teachers.request_services.create', ['students' => $student, 'teachers' => $teachers]);
    }

    public function store(Request $request)
    {
        $form_id = Form::query()->where('title', '=', $request->form_title)->first()->id ?? null;
        if (is_null($form_id)){
            session()->flash('error', 'فشلت عملية تقديم الطلب');
            return redirect()->back();
        }

        $uniqid = uniqid();
        foreach ($request->input('service') as $key => $value) {
            Service::setValue($key, $value, $form_id, $uniqid);
        }

        // write here some insert data logic
//        Service::setValue('', '', $form_id);

        session()->flash('success', 'تم تقديم الطلب بنجاح');
        return redirect()->back();
    }
}
