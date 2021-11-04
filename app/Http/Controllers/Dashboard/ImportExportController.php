<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExamRequestsExport;
use App\Exports\MonthlyScoresExport;
use App\Http\Controllers\Controller;
use App\Imports\ChapterImport;
use App\Imports\ClassesTeachersImport;
use App\Imports\LessonImport;
use App\Imports\PartImport;
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
//        ini_set('post_max_size', "516M");
//        ini_set('upload_max_filesize', "512M");
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
        Excel::import(new LessonImport(), 'lesson_pages.xlsx');
        return 'Done';
    }

    public function importChapters()
    {
        Excel::import(new ChapterImport(), 'chapters.xlsx');
        return 'Done';
    }

    public function importParts()
    {
        Excel::import(new PartImport(), 'parts.xlsx');
        return 'Done';
    }

    public function exportExamsRequests(Request $request)
    {
//        $request->validate([
//            'date_from' => 'required|date',
//            'date_to' => 'required|date',
//        ]);

        return Excel::download(new ExamRequestsExport($request->date_from, $request->date_to), 'reports.xlsx');
    }

//    public function exportMonthlyScoresIndex()
//    {
//        return view('admins.attendance.create');
//    }
    public function exportMonthlyScores(Request $request)
    {
        if (!isset($request->month_year)){
//            $request['month_year'] = date("Y" . "-" . date('m'));
            $request['month_year'] = "2021-10";
        }
        return Excel::download(new MonthlyScoresExport($request->month_year), 'monthly-scores.xlsx');
    }
}
