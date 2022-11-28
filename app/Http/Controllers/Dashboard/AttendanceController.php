<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\AbsencesExport;
use App\Exports\AttendanceExport;
use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use App\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Type\Time;

class AttendanceController extends Controller
{
    public function create()
    {
        return view('admins.attendance.create');
    }

    public function store(Request $request)
    {
        $time = Carbon::now()->addHour(8);
        $current_time = $time->hour . ':' . $time->minute;

        $status = Attendance::query()->create([
            'type' => $request->logout_btn ?? $request->login_btn,
            'period' => getAttendancePeriod($request->login_btn, $current_time),
            'admin_id' => auth()->guard('admin_web')->user()->id,
            'created_at' => $time,
        ]);

        if (!$status){
            session()->forget('success');
            session()->flash('error', 'فشلت عملية تسجيل البصمة');
        }

        return redirect()->back();
    }

    public function exportIndex()
    {
        return view('admins.import_export.export_attendance');
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
