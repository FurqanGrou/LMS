<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {

        if ($request->has('teacher')) {
            $audits = \OwenIt\Auditing\Models\Audit::with('user')
            ->where('user_type','like','%Teacher%')
            ->where('user_id',$request['teacher'])
                ->orderBy('created_at', 'desc')->get();
        } else {
            $audits = \OwenIt\Auditing\Models\Audit::with('user')
                ->orderBy('created_at', 'desc')->get();
        }

        return view('admins.audits.index', compact('audits'));
    }
}
