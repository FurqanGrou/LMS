<?php

namespace App\Http\Controllers\Teachers;

use App\ClassesTeachers;
use App\DataTables\AllClassesDatatable;
use App\DataTables\ClassesDataTable;
use App\JoinRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassesController extends Controller
{
    public function index(AllClassesDatatable $allClassesDatatable)
    {
        return $allClassesDatatable->render('teachers.classes.all');
    }

    public function joinRequest(Request $request){
        if($request->request_status){
            JoinRequest::query()
                ->where('teacher_email', '=', auth()->guard('teacher_web')->user()->email)
                ->where('class_number', '=', $request->class_number)
                ->delete();
        }else{
            JoinRequest::query()->updateOrCreate([
                'teacher_email' => auth()->guard('teacher_web')->user()->email,
                'class_number' => $request->class_number,
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
