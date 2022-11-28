<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Report;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
//    public function index(Request $request)
//    {
//
//        $teachers = Teacher::query()->get();
//        $students = User::query()->get();
//
//        if($request->has('teacher')){
//            $audits = Audit::with('user')
//                ->where('user_type', 'like','%Teacher%')
//                ->where('user_id', $request['teacher'])
//                ->where('event', '!=', 'created')
//                ->orderBy('created_at', 'desc')
//                ->paginate(25);
//        }else{
//            $audits = Audit::with('user')
//                ->where('event', '!=', 'created')
//                ->orderBy('created_at', 'desc')
//                ->paginate(25);
//        }
//
//        return view('admins.audits.index', ['audits' => $audits, 'teachers' => $teachers, 'students' => $students]);
//    }

    public function index()
    {
        $teachers = Teacher::query()->get();
        $students = User::query()->get();

        $student = null;
        if (request()->student_id) {
            $student = User::query()->findOrFail(request()->student_id);
            $reports = Report::query()
                ->where('student_id', '=', request()->student_id)
                ->where('created_at', '>=', request()->date_from)
                ->where('created_at', '<=', request()->date_to)
                ->get();
            $audits = Audit::with('user')
                ->whereIn('auditable_id', $reports->pluck('id')->toArray())
                ->where('auditable_type', 'like', '%Report%')
//                ->orderBy('updated_at', 'desc')
                ->paginate(30);
        }else{
            $audits = Audit::with('user')
                ->where('event', '!=', 'created')
                ->orderBy('created_at', 'desc')
                ->paginate(30);
        }

        return view('admins.audits.index', ['audits' => $audits, 'teachers' => $teachers, 'student' => $student, 'students' => $students]);
    }
}
