<?php

namespace App\Http\Controllers\Teachers;

use App\DataTables\AttendanceTeachersDatatable;
use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Attendance;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(AttendanceTeachersDatatable $attendanceTeachersDatatable)
    {
        return $attendanceTeachersDatatable->render('teachers.attendance.index');
    }

    public function create()
    {
        return view('teachers.attendance.create');
    }

    public function store(Request $request)
    {
        $current_time = Carbon::now()->hour . ':' . Carbon::now()->minute;
        $period = '';

        if (isset($request->login_btn)){
            $type = 'login';
            if($current_time >= '23:00' && $current_time <= '3:00'){
                $period = 'الفترة الصباحية';
            }

            if($current_time >= '5:30' && $current_time <= '9:00'){
                $period = 'الفترة المسائية 1';
            }

            if($current_time >= '10:00' && $current_time <= '13:00'){
                $period = 'الفترة المسائية 2';
            }

            if($current_time >= '13:30' && $current_time <= '17:00'){
                $period = 'الفترة المسائية 3';
            }

            if($current_time >= '17:01' && $current_time <= '20:00'){
                $period = 'الفترة المسائية 4';
            }
            session()->flash('success', 'تم تسجيل بصمة الدخول بنجاح');
        }else{
            $type = 'logout';
            if($current_time >= '1:55' && $current_time <= '6:00'){
                $period = 'الفترة الصباحية';
            }

            if($current_time >= '7:55' && $current_time <= '11:30'){
                $period = 'الفترة المسائية 1';
            }

            if($current_time >= '11:55' && $current_time <= '15:55'){
                $period = 'الفترة المسائية 2';
            }

            if($current_time >= '16:00' && $current_time <= '18:50'){
                $period = 'الفترة المسائية 3';
            }

            if($current_time >= '18:55' && $current_time <= '22:30'){
                $period = 'الفترة المسائية 4';
            }
            session()->flash('success', 'تم تسجيل بصمة الخروج بنجاح');
        }

        $status = Attendance::query()->create([
            'last_4_id' => auth()->guard('teacher_web')->user()->last_4_id ?? 0,
            'employee_number' => auth()->guard('teacher_web')->user()->teacher_number,
            'full_name' => auth()->guard('teacher_web')->user()->name,
            'section' => auth()->guard('teacher_web')->user()->section,
            'type' => $type,
            'period' => $period,
            'teacher_id' => auth()->user()->id,
            'action_code' => uniqid(),
        ]);

        if (!$status){
            session()->forget('success');
            session()->flash('error', 'فشلت عملية تسجيل البصمة');
        }

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        if (!isset($request->type)){
            $request->mail_status = -1;
        }
        return Excel::download(new AttendanceExport($request->date_from, $request->date_to, $request->type), 'attendances.xlsx');
    }

}
