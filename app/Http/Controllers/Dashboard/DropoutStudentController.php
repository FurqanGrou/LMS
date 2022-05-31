<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\DropoutStudentDatatable;
use App\DataTables\DropoutStudentDetailsDatatable;
use App\DropoutStudent;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DropoutStudentController extends Controller
{
    public function index(DropoutStudentDatatable $dropoutStudentDatatable)
    {
        return $dropoutStudentDatatable->render('admins.dropout_student.index');
    }

    public function show(User $user)
    {
        $studentDetailsDatatable = new DropoutStudentDetailsDatatable();
        return $studentDetailsDatatable->with(['student_id' => $user->id])->render('admins.dropout_student.show', ['student_name' => $user->name]);
    }

    public function sendAlert(Request $request)
    {
        dd($request->all());
    }
}
