<?php

namespace App\Http\Controllers\Teachers;

use App\Chapter;
use App\ClassesTeachers;
use App\ExamRequest;
use App\Part;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamRequestController extends Controller
{
    public function index()
    {
        return '';
    }

    public function create()
    {
        $teacher_email   = auth()->guard('teacher_web')->user()->email;
        $classes_numbers = ClassesTeachers::query()->select(['class_number'])->where('teacher_email', '=', $teacher_email)->get()->pluck('class_number')->toArray();
        $students = User::query()->whereIn('class_number', $classes_numbers)->get();
        $teachers = Teacher::query()->get();
        $chapters = Part::query()->get();

        return view('teachers.request_services.exams.create', ['students' => $students, 'teachers' => $teachers, 'chapters' => $chapters]);
    }

    public function store(Request $request)
    {
        ExamRequest::create([
            'user_id' => $request->student_id,
            'chapter_id' => $request->chapter_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'teacher_name' => $request->teacher_name,
            'teacher_id' => auth()->guard('teacher_web')->user()->id,
        ]);

        session()->flash('success', 'تم تقديم الطلب بنجاح');
        return redirect()->back();
    }
}
