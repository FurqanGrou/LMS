<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\TopTrackerExport;
use App\Http\Controllers\Controller;
use App\Imports\TopTrackerImport;
use App\TopTrackerEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class TopTrackerController extends Controller
{

    public function importTopTrackerIndex()
    {
        return view('admins.import_export.import_top_tracker_employees');
    }

    public function importEmployees(Request $request)
    {
        Excel::import(new TopTrackerImport(), $request->file);

        Artisan::call('cache:clear');
        return redirect()->back()->with('success', 'تم تحديث بيانات المعلمين بنجاح');
    }

    public function exportTopTrackerIndex()
    {
        return view('admins.import_export.export_top_tracker_report');
    }

    public function exportReports(Request $request)
    {

        $endpoint = config('services.top_tracker.endpoint');

        $response = Http::get($endpoint, [
            "start_date" => $request->start_date,
            "end_date"   => $request->end_date,
            "access_token" => config('services.top_tracker.access_token'),
            "project_ids"  => 'all',
            "worker_ids"   => 'all',
        ]);

        $result = collect($response->json()['activities'])->groupBy(['worker.name', function($data){
            return Carbon::parse($data['start_time'])->format('d-m-Y');
        }])->map(function ($value){

            $employees = [];
            foreach ($value as $key => $item){
                $last_index  = count($item)-1;
                $worker_name = $item[0]['worker']['name'];

                $start_time  = Carbon::parse($item[$last_index]['start_time'])->setTimezone('Asia/Riyadh');
                $start_time  = $start_time->format('d-m-Y h:i:s');

                $end_time    = Carbon::parse($item[0]['end_time'])->setTimezone('Asia/Riyadh');
                $end_time    = $end_time->format('d-m-Y h:i:s');

                $total_seconds = $item->pluck('seconds')->sum();

                array_push($employees, [
                    'Nationality No.' => getEmployeeInfo($worker_name, 'nationality_no'),
                    'Employee No.'    => getEmployeeInfo($worker_name, 'employee_no'),
                    'Employee Section'    => getEmployeeInfo($worker_name, 'section'),
                    'Employee Type'    => getEmployeeInfo($worker_name, 'type'),
                    'Name' => $worker_name,
                    'Start Time' => $start_time,
                    'End Time'   => $end_time,
                    'Duration'   => $total_seconds,
                    'Period'     => getStartTimePeriod($item[$last_index]['start_time']),
//                    dd($item[$last_index]['start_time'])
                ]);
            }

            return $employees;
        });

        return Excel::download(new TopTrackerExport($result), 'top-tracker-report.xlsx');
    }

}
