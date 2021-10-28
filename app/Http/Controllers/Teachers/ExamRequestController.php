<?php

namespace App\Http\Controllers\Teachers;

use App\Chapter;
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
        $students = User::all();
        $teachers = Teacher::all();
        $chapters = Part::all();

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
