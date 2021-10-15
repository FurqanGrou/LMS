<?php

namespace App\Http\Controllers\Teachers;

use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function indesx(){

        dd(auth()->user());

//        return view('dashboard.index', compact('statistics'));
    }
}
