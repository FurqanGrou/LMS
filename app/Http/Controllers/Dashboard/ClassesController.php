<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ClassesDataTable;
use App\DataTables\ClassStudentsDatatable;
use App\DataTables\UserDatatable;
use App\Http\Controllers\Controller;
use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassesController extends Controller
{

    public function index(ClassesDataTable $classes)
    {
        return $classes->render('admins.classes.index');
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

        return $classStudents->with('class_number', request()->class_number)->render('admins.students.index', ['remaining' => $remaining]);
    }

}
