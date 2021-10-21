<?php

namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestServiceController extends Controller
{
    public function create()
    {
        return view('teachers.request_services.create');
    }
}
