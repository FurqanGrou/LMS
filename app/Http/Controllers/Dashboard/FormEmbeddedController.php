<?php

namespace App\Http\Controllers\Dashboard;

use App\FormEmbedd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class FormEmbeddedController extends Controller
{

    public function index(){
        $forms = FormEmbedd::query()->paginate();
        return view('admins.form_embeddes.forms', ['forms' => $forms]);
    }

    public function edit($id){
        $formEmbedd = FormEmbedd::query()->findOrFail($id);
        return view('admins.form_embeddes.forms_edit', ['form' => $formEmbedd]);
    }

    public function create(){

        return view('admins.form_embeddes.create');
    }

    public function update(Request $request, $id){
        $form = FormEmbedd::query()->findOrFail($id);

        $form->update([
            'url'    => $request->url,
            'title'  => $request->title,
            'status' => $request->status ? '1' : '0',
        ]);

        return redirect()->route('admins.forms-service.edit', $form->id)->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function store(Request $request){

        FormEmbedd::query()->create([
            'url'    => $request->url,
            'title'  => $request->title,
            'status' => $request->status ? '1' : '0',
        ]);

        return redirect()->route('admins.forms-service.index')->with('success', 'تمت إضافة الطلب بنجاح');
    }

//    public function destroy($id){
//        $form = FormEmbedd::query()->findOrFail($id);
//        return redirect()->route('admins.forms-service.edit', $form->id)->with('success', 'تم تحديث البيانات بنجاح');
//    }

}
