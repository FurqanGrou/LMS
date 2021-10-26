<?php

namespace App\Http\Controllers\Dashboard;

use App\Admin;
use App\Classes;
use App\Report;
use App\Teacher;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(){

        $tomorrow = Carbon::tomorrow();
        $today = Carbon::today();

        if(str_contains($tomorrow->format('l') ,'Friday')){
            $tomorrow->addDays(2);
        }

        $statistics = [];
        $statistics['teachers'] = Teacher::query()->count();
        $statistics['students'] = User::query()->count();
        $statistics['regular_students'] = User::query()->whereNotNull('class_number')->count();
        $statistics['classes'] = Classes::query()->count();
        $statistics['admins'] = Admin::query()->count();
        $statistics['last_report'] = Report::query()->orderBy('updated_at', 'DESC')->first()->updated_at->diffForHumans();

        $statistics['sent_messages'] = Report::query()
                                    ->where('mail_status', '=', '1')
                                    ->whereMonth('created_at', '=', $today->month)
                                    ->whereDay('created_at', '=', $today->day)
                                    ->whereYear('created_at', '=', $today->year)
                                    ->count();

        $statistics['not_sent_messages'] = $statistics['regular_students'] - $statistics['sent_messages'];

        return view('admins.index', compact('statistics'));
    }
}
