<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\UserDatatable;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(UserDatatable $userDatatable)
    {
        return $userDatatable->render('admins.students.index');
    }
}
