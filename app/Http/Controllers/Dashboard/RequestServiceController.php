<?php

namespace App\Http\Controllers\Dashboard;

use App\ClassesTeachers;
use App\DataTables\RequestServiceDatatable;
use App\Form;
use App\Http\Controllers\Controller;
use App\Service;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;

class RequestServiceController extends Controller
{
//    public function index()
//    {
//
//        $requests = Service::query()
//            ->with('form')
//            ->select(['request_code', 'form_id'])
//            ->distinct()->get();
//
//        dd($requests[0]->form->title);
//    }

    public function index(RequestServiceDatatable $requestServiceDatatable)
    {
        return $requestServiceDatatable->render('admins.request_services.index');
    }

    public function show(Request $request)
    {

        $request_type = Service::query()->where('request_code', '=', $request->service)->with('form')->first();
        $request_type = $request_type->form->title;

        $values = Service::query()->where('request_code', '=', $request->service)->pluck('value', 'name')->toArray();

        return view('admins.request_services.show', ['values' => $values, 'request_type' => $request_type, 'request_code' => $request->service]);
    }

    public function update(Request $request)
    {
        if($request->form_title == 'طلب اختبار'){
            foreach ($request->service as $key => $value){
                Service::query()
                    ->where('request_code', '=', $request->request_code)
                    ->where('name', '=', $key)
                    ->update([
                        'value' => $value
                    ]);
            }

            return 'Done';
        }
    }
}
