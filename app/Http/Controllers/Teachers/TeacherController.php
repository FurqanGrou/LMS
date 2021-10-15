<?php

namespace App\Http\Controllers\Teachers;
use App\DataTables\ClassStudentsDatatable;
use App\Http\Controllers\Controller;

use App\Classes;
use App\DataTables\TeachertDatatable;
use App\Report;
use App\Teacher;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{

    public function index(TeachertDatatable $teacher)
    {

//        $time = Carbon::now();
//        dd($time->format('g:i A'));

        return $teacher->render('teachers.classes.index');
    }

    public function classStudents(ClassStudentsDatatable $classStudents)
    {
        $tomorrow_date = Carbon::tomorrow();
        $tomorrow_date_check = Carbon::tomorrow();
        if(str_contains($tomorrow_date->format('l') ,'Friday')){
            $tomorrow_date->addDays(2);
            $tomorrow_date_check = $tomorrow_date_check->addDays(2);
        }

        $studentsCount = User::where('class_number', '=', request()->class_number)->count();

        $reportsCount = Report::whereDate('created_at', '=', $tomorrow_date_check)->where('class_number', '=', request()->class_number)->count();

        $remaining = $studentsCount - $reportsCount;

        return $classStudents->with('class_number', request()->class_number)->render('teachers.class_students.index', ['remaining' => $remaining]);
    }

}
