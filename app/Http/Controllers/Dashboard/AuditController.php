<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Report;
use App\Teacher;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index(Request $request)
    {

        $teachers = Teacher::query()->get();

        if($request->has('teacher')){
            $audits = Audit::with('user')
                ->where('user_type', 'like','%Teacher%')
                ->where('user_id', $request['teacher'])
                ->where('event', '!=', 'created')
                ->orderBy('created_at', 'desc')
                ->paginate(25);
        }else{
            $audits = Audit::with('user')
                ->where('event', '!=', 'created')
                ->orderBy('created_at', 'desc')
                ->paginate(25);
        }

        return view('admins.audits.index', ['audits' => $audits, 'teachers' => $teachers]);
    }
}
