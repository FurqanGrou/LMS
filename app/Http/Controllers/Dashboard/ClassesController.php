<?php

namespace App\Http\Controllers\Dashboard;

use App\ClassesTeachers;
use App\DataTables\ClassesDataTable;
use App\DataTables\ClassStudentsDatatable;
use App\DataTables\JoinRequestsDatatable;
use App\Http\Controllers\Controller;
use App\JoinRequest;
use App\Teacher;
use Illuminate\Http\Request;

class ClassesController extends Controller
{

    public function index(ClassesDataTable $classes)
    {
        return $classes->render('admins.classes.index');
    }

    public function classStudents(ClassStudentsDatatable $classStudents)
    {
//        $tomorrow_date = Carbon::tomorrow();
//        $tomorrow_date_check = Carbon::tomorrow();
//        if(str_contains($tomorrow_date->format('l') ,'Friday')){
//            $tomorrow_date->addDays(2);
//            $tomorrow_date_check = $tomorrow_date_check->addDays(2);
//        }
//
//        $studentsCount = User::where('class_number', '=', request()->class_number)->count();
//
//        $reportsCount = Report::whereDate('created_at', '=', $tomorrow_date_check)->where('class_number', '=', request()->class_number)->count();
//
//        $remaining = $studentsCount - $reportsCount;

//        return $classStudents->with('class_number', request()->class_number)->render('admins.students.index', ['remaining' => $remaining]);

        return $classStudents->with('class_number', request()->class_number)->render('admins.students.index');
    }

    public function joinRequests(JoinRequestsDatatable $joinRequestsDatatable)
    {
        return $joinRequestsDatatable->render('admins.classes.join_requests');
    }

    public function respondRequest(Request $request)
    {
        $type = Teacher::query()->where('email', '=', $request->teacher_email)->first()->section;

        $result = ClassesTeachers::query()->create([
           'teacher_email' => $request->teacher_email,
           'class_number' => $request->class_number,
            'type' => $type,
            'role' => 'spare',
        ]);

        if ($result){
            JoinRequest::query()
                ->where('teacher_email', '=', $request->teacher_email)
                ->where('class_number', '=', $request->class_number)
                ->delete();
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'failed'], 500);
    }
}
