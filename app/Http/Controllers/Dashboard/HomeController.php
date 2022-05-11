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

        $statistics['students'] = User::query();
        if(isHasUserType('super_admin')){
            $statistics['students'] = $statistics['students']->count();
        }else{
            $statistics['students'] = $statistics['students']->where('study_type', '=', getUserType() == 'iksab' ? 1 : 0)->count();
        }

        $statistics['regular_students'] = User::query()->whereNotNull('class_number');
        if(isHasUserType('super_admin')){
            $statistics['regular_students'] = $statistics['regular_students']->count();
        }else{
            $statistics['regular_students'] = $statistics['regular_students']->where('study_type', '=', getUserType() == 'iksab' ? 1 : 0)->count();
        }

        $statistics['classes'] = Classes::query();
        if(isHasUserType('super_admin')){
            $statistics['classes'] = $statistics['classes']->count();
        }else{
            $statistics['classes'] = $statistics['classes']->where('study_type', '=', getUserType() == 'iksab' ? 1 : 0)->count();
        }

        $statistics['admins'] = Admin::query();
        if(isHasUserType('super_admin')){
            $statistics['admins'] = $statistics['admins']->count();
        }else{
            $statistics['admins'] = $statistics['admins']->where('user_type', '=', getUserType())->count();
        }

        $statistics['last_report'] = Report::query()->orderBy('updated_at', 'DESC')->first()->updated_at->timezone('Asia/Riyadh')->diffForHumans();

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
