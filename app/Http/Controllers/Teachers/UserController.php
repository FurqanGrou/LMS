<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use App\DataTables\UserDatatable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UserDatatable $userDatatable)
    {
        return $userDatatable->render('dashboard.admins.index');
    }
}
