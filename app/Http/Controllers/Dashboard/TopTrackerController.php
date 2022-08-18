<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\TopTrackerExport;
use App\Http\Controllers\Controller;
use App\Imports\TopTrackerImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class TopTrackerController extends Controller
{
    public function index()
    {
        $url = "https://tracker-api.toptal.com/reports/activities";
        $response = Http::get($url, [
            "start_date" => '2022-08-15',
            "end_date" => '2022-08-16',
            "access_token" => "WFhlMFJlQnBPWEI4TmpXYk5FcjUwRXNxNURFbXUwamFkNlpZYnRPNGE5MzBZdEs2TkErWFA4YUcyMzFoeEltRy0tK2NZQlZ6YjQxTG12T0sxYmh1aFN6dz09--81bd154eb4841cf93f17f4294c8175ed0ce8461c",
            "project_ids" => 'all',
            "worker_ids" => 'all',
        ]);

        $result = collect($response->json()['activities'])->map(function ($value){

            dd($value);
            $last_index = count($value)-1;
            return [
//                'employee_id' => $value[0]['worker']['name'],
//                'nationality_no' => $value[0]['worker']['name'],

                'worker_name' => $value[0]['worker']['name'],
                'start_time'  =>  $value[$last_index]['start_time'],
                'end_time'    =>  $value[0]['end_time'],
                'total_seconds' =>  $value->pluck('seconds')->sum(),
            ];
        });

        return Excel::download(new TopTrackerExport($result), 'top-tracker-report.xlsx');

        return $result;

//        $total_seconds = collect($response->json()['activities'])->where('worker.id', '=', 257853)->pluck('seconds')->sum();
//        $start_time = collect($response->json()['activities'])->where('worker.id', '=', 257853)->last()['start_time'];
//        $end_time = collect($response->json()['activities'])->where('worker.id', '=', 257853)->first()['end_time'];
//
//        return ['start_time' => $start_time, 'end_time' => $end_time, 'total_seconds' => $total_seconds];



//        $results = $response->json()['reports']['workers']['data'];
//
//        $new_results = [];
//        foreach ($results as $key_1 => $result){
////            dd(count($result['dates']));
//
//            foreach ($result['dates'] as $key_2 => $date){
//                if ($date['seconds'] == 0){
//                    unset($results[$key_1]['dates'][$key_2]);
//                }
//            }
//
//
//            if ( count($results[$key_1]['dates']) == 0 ){
//                unset($results[$key_1]);
//            }
//
//        }
//
//        return ['workers' => $results];
    }

    public function getWorkers()
    {
        $url = "https://tracker-api.toptal.com/reports/filters?access_token=WFhlMFJlQnBPWEI4TmpXYk5FcjUwRXNxNURFbXUwamFkNlpZYnRPNGE5MzBZdEs2TkErWFA4YUcyMzFoeEltRy0tK2NZQlZ6YjQxTG12T0sxYmh1aFN6dz09--81bd154eb4841cf93f17f4294c8175ed0ce8461c";

    }

    public function import()
    {
        Excel::import(new TopTrackerImport(), 'employees-toptracker.xlsx');
    }
}
