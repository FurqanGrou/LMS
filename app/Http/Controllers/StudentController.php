<?php

namespace App\Http\Controllers;

use App\Classes;
use App\DataTables\StudentDatatable;
use App\Report;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use App\DataTables\AdDatatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class StudentController extends Controller
{
//    public function index(StudentDatatable $student)
//    {
//        return $student->render('students.student.index');
//    }

    public function index()
    {

        $zoom_link = Classes::where('class_number', '=', auth()->user()->class_number)->first()->zoom_link;
        $teacher_number = DB::table('classes_teachers')
                        ->select('teacher_number')
                        ->where('class_number', '=', auth()->user()->class_number)
                        ->first();

        $teacher_name = Teacher::where('teacher_number', '=', $teacher_number->teacher_number)->where('section', '=', auth()->user()->section)->first()->name;

        return view('students.student.index2', compact('zoom_link', 'teacher_name'));
    }

    public function showReport($id){
        $currentMonth = date('m');
        $monthReports = Report::whereRaw('MONTH(created_at) = ?', [$currentMonth])->where('student_id', '=', $id)->get();

        return view('students.reports.show', [ 'monthReports' => $monthReports, 'student_id' => $id ]);
    }

    public function generate_pdf($student_id)
    {
        $currentMonth = date('m');
        $monthReports = Report::whereRaw('MONTH(created_at) = ?', [$currentMonth])->where('student_id', '=', $student_id)->get();
//        return view('students.reports.download', [ 'monthReports' => $monthReports ]);

        $pdf = PDF::loadView('students.reports.download', compact('monthReports', $monthReports));
        return $pdf->stream('document.pdf');
    }

}
