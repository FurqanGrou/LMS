<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\HolidayRequest;
use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HolidayController extends Controller
{
    public function index()
    {

        $students = Cache::remember('students.holidays',60 * 60 * 24, function(){
            return User::query()->whereNotNull('class_number')->orderBy('student_number', 'ASC')->get();
        });

        return view('admins.holidays.index', ['students' => $students]);
    }

    public function store(Request $request)
    {

        $request->validate([
           'date_from' => 'required|date',
           'date_to'   => 'required|date',
           'action_type'  => 'required',
           'student_id'   => 'required|array|exists:users,id'
        ]);

        if($request->action_type == 'null'){
            return redirect()->back()->withError('يجب عليك إختيار نوع الحركة');
        }

        if ($request->action_type == 'delete'){
            $from = date($request->date_from);
            $to   = date($request->date_to);

            Report::query()
                ->whereIn('student_id', $request->student_id)
                ->whereBetween('created_at', [$from, $to])
                ->delete();

            return back()->withSuccess('تمت إزالة الاجازات للطلاب المحددين بنجاح');
        }

        if($request->action_type == 'assign'){
            dispatch(new HolidayRequest($request->student_id, $request->date_from, $request->date_to, auth('admin_web')->user()->email))->onQueue('HolidayRequest')->allOnQueue('HolidayRequest');
        }

        return back()->withSuccess('بدأت عملية تعيين الاجازات الشهرية بنجاح ستصلك رسالة عبر البريد الالكتروني تفيد إتمام العملية');
    }
}
