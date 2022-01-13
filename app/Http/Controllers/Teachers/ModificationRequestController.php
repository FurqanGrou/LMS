<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Mail\ModificationRequestMail;
use App\ModificationRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ModificationRequestController extends Controller
{
    public function create()
    {
        $students = User::query()->get();
        return view('teachers.modification-request.create', ['students' => $students]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'new_grades' => 'required',
        ], [
            'student_id.required' => 'يجب إختيار طالب من القائمة',
            'student_id.exists' => 'يجب إختيار طالب من القائمة',
            'date.required' => 'يجب إختيار تاريخ صحيح',
            'date.date'     => 'يجب إختيار تاريخ صحيح',
            'new_grades.required' => 'يجب عليك إدخال الدرجات الجديدة المطلوبة',
        ]);

        $result = ModificationRequest::create([
            'student_id' => $request->student_id,
            'date' => $request->date,
            'new_grades' => $request->new_grades,
            'notes' => $request->notes,
            'teacher_id' => auth()->guard('teacher_web')->user()->id,
        ]);

        $to_emails = ['lmsfurqan1@gmail.com'];
        Mail::to($to_emails)
            ->bcc(['lmsfurqan1@gmail.com'])
            ->send(new ModificationRequestMail($result));

        if(!empty(Mail::failures())) {
            return back()->withError('فشلت عملية ارسال الطلب');
        }

        return back()->withSuccess('تم تقديم الطلب بنجاح');
    }
}
