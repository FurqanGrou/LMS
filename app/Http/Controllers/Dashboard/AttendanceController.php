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
            'last_4_id' => auth()->guard('admin_web')->last_4_id ?? 0,
            'employee_number' => auth()->guard('admin_web')->user()->employee_number,
            'full_name' => auth()->guard('admin_web')->user()->name,
            'section' => auth()->guard('admin_web')->user()->section,
            'type' => $type,
            'period' => $period,
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
