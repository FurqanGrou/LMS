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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{

    public function index(TeachertDatatable $teacher)
    {
        return $teacher->render('teachers.classes.index');
    }

    public function classStudents(ClassStudentsDatatable $classStudents)
    {
//        $tomorrow_date = Carbon::tomorrow();
//        $tomorrow_date_check = Carbon::tomorrow();
//        if(str_contains($tomorrow_date->format('l') ,'Friday')){
//            $tomorrow_date->addDays(2);
//            $tomorrow_date_check = $tomorrow_date_check->addDays(2);
//        }

//        $studentsCount = User::where('class_number', '=', request()->class_number)->count();

//        $reportsCount = Report::whereDate('created_at', '=', $tomorrow_date_check)->where('class_number', '=', request()->class_number)->count();
//
//        $remaining = $studentsCount - $reportsCount;

//        return $classStudents->with('class_number', request()->class_number)->render('teachers.class_students.index', ['remaining' => $remaining]);

        $class = Cache::remember('class_info.' . request()->class_number,60 * 60 * 60,function(){
            return Classes::where('class_number', '=', request()->class_number)->first();
        });

        return $classStudents->with('class_number', request()->class_number)->render('teachers.class_students.index', ['class' => $class]);
    }

    public function changePasswordView(Teacher $teacher)
    {
        return view('teachers.account.change_password', ['teacher' => $teacher]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Auth::guard('teacher_web')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            session()->flash('error', 'يرجى التأكد من كلمة المرور الحالية');
            return redirect()->back();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('success', 'تم تحديث كلمة المرور بنجاح');

        return redirect()->back();
    }


}
