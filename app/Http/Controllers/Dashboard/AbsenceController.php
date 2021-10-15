<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\AbsenceDatatable;
use App\Http\Controllers\Controller;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AbsenceDatatable $absenceDatatable)
    {
        return $absenceDatatable->render('admins.absences.index');
    }

    public function absenceType(Request $request)
    {
        $date = Carbon::today();
        $date = Carbon::createFromDate($date->year, $date->month, $date->day)->format('l d-m-Y');

        if(isset(request()->date_filter)){
            $date = new Carbon(request()->date_filter);
            $date = $date->format('l d-m-Y');
        }

        Report::query()
            ->where('id', '=', $request->report_id)
            ->where('date', '=', $date)
            ->update(['absence' => $request->absence_type]);

        return $request->report_id;
        return response()->json(['status' => 'success'], 200);
    }

}
