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
        $time = Carbon::now()->addHour(8);
        $current_time = $time->hour . ':' . $time->minute;

        $status = Attendance::query()->create([
            'type' => $request->logout_btn ?? $request->login_btn,
            'period' => getAttendancePeriod($request->login_btn, $current_time),
            'teacher_id' => auth()->guard('teacher_web')->user()->id,
            'created_at' => $time,
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
