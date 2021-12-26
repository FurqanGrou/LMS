<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\AbsenceDatatable;
use App\Exports\AbsencesExport;
use App\Http\Controllers\Controller;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

        $report = Report::where('id', '=', $request->report_id)
            ->where('date', '=', $date)->first();

        $report->update(['absence' => $request->absence_type]);

        return response()->json(['status' => 'success'], 200);
    }

    public function export(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        if (!isset($request->absence_status)){
            $request->absence_status = -1;
        }
        return Excel::download(new AbsencesExport($request->date_from, $request->date_to, $request->absence_status), 'reports.xlsx');
    }

}
