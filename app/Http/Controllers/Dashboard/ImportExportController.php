<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\ChapterImport;
use App\Imports\ClassesTeachersImport;
use App\Imports\LessonImport;
use App\Report;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use App\Imports\ClassesImport;
use App\Imports\UsersImport;
use App\Imports\TeachersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ImportExportController extends Controller
{
    public function __construct(){
        ini_set('max_execution_time', 600);
    }

    public function importStudentsView()
    {
        $teachers_status= Teacher::query()->orderBy('updated_at', 'DESC')->first()->status;

        return view('admins.import_export.import', compact('teachers_status'));
    }

    public function importStudents()
    {
        Excel::import(new UsersImport, request()->file('file'));

        // update student class number in reports table
        $students = User::query()->get();
        foreach ($students as $student){
            if (!is_null($student->class_number)){
                Report::where('student_id', $student->id)
                    ->update(['class_number' => $student->class_number]);
            }
        }

        Teacher::query()->update(['status' => 1]);

        return redirect()->back()->with('success', 'تم تحديث بيانات الطلاب بنجاح');
    }

    public function importLessons()
    {
        Excel::import(new LessonImport(), 'lessons.xlsx');
        return 'Done';
    }

    public function importChapters()
    {
        Excel::import(new ChapterImport(), 'chapters.xlsx');
        return 'Done';
    }

}
