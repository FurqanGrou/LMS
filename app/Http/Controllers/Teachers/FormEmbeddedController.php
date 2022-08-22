<?php

namespace App\Http\Controllers\Teachers;


use App\FormEmbedd;
use App\Http\Controllers\Controller;

class FormEmbeddedController extends Controller
{

    public function show(FormEmbedd $formEmbedd){
        if ($formEmbedd->status){
            return view('teachers.form-embedded.form', ['form'=> $formEmbedd]);
        }

        abort(403);
    }

}
