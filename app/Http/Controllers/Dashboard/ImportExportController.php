<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExamRequestsExport;
use App\Exports\MonthlyScoresExport;
use App\Http\Controllers\Controller;
use App\Imports\ChapterImport;
use App\Imports\ClassesTeachersImport;
use App\Imports\LessonImport;
use App\Imports\PartImport;
use App\Jobs\ExportMonthlyScores;
use App\Report;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use App\Imports\ClassesImport;
use App\Imports\UsersImport;
use App\Imports\TeachersImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ImportExportController extends Controller
{
    public function __construct(){
//        ini_set('max_execution_time', 600);
//        ini_set('memory_limit', '60m');

//        ini_set('post_max_size', "516M");
//        ini_set('upload_max_filesize', "512M");
    }

    //    ==============================================
    public function importFaceToFaceStudentsView()
    {
        $teachers_status= Teacher::query()->orderBy('updated_at', 'DESC')->first()->status;

        return view('admins.import_export.import_face_to_face_students', compact('teachers_status'));
    }
    public function importOnlineStudentsView()
    {
        $teachers_status= Teacher::query()->orderBy('updated_at', 'DESC')->first()->status;

        return view('admins.import_export.import_online_students', compact('teachers_status'));
    }

    public function importFaceToFaceStudents()
    {
        $study_type = 1; // 0 is online, 1 is face to face

        Excel::import(new UsersImport($study_type), request()->file('file'));

        // update student class number in reports table
//        $students = User::query()->get();
//        foreach ($students as $student){
//            if (!is_null($student->class_number)){
//                Report::where('student_id', $student->id)
//                    ->update(['class_number' => $student->class_number]);
//            }
//        }

        Teacher::query()->update(['status' => 1]);
        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'تم تحديث بيانات الطلاب بنجاح');
    }
    public function importOnlineStudents()
    {
        $study_type = 0; // 0 is online, 1 is face to face

        Excel::import(new UsersImport($study_type), request()->file('file'));

        Teacher::query()->update(['status' => 1]);
        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'تم تحديث بيانات الطلاب بنجاح');
    }
    //    ==============================================

    public function importLessons()
    {
        Excel::import(new LessonImport(), 'noorania_pages.xlsx');
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

        return Excel::download(new ExamRequestsExport($request->date_from, $request->date_to), 'exams-requests.xlsx');
    }

    public function exportMonthlyScoresIndex()
    {
        return view('admins.import_export.export_monthly_scores');
    }

    public function exportMonthlyScores(Request $request)
    {
        if (!isset($request->month_year)){
            $request['month_year'] = date("Y" . "-" . date('m')); // 2022-01
        }

        if (!isset($request->mail_status)){
            $request['mail_status'] = -1;
        }

        if ($request->mail_status == 0){
            $status = 'unsent';
        }elseif($request->mail_status == 1){
            $status = 'sent';
        }else{
            $status = 'all';
        }

        $file_name = $request->month_year . "-monthly-scores-$status.xlsx";
        dispatch(new ExportMonthlyScores($request->month_year, $request->mail_status, $file_name, auth()->user()->email))->onQueue('ExportMonthlyScores')->allOnQueue('ExportMonthlyScores');

        return back()->withSuccess('بدأت عملية إستخراج نتائج التقارير الشهرية بنجاح ستصلك رسالة عبر البريد الالكتروني تحمل رابط التنزيل');
    }
}
