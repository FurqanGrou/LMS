<?php

namespace App\Http\Controllers\Dashboard;

use App\AttendanceAbsenceRequests;
use App\Classes;
use App\ClassesTeachers;
use App\DataTables\RequestServiceDatatable;
use App\Form;
use App\Http\Controllers\Controller;
use App\Service;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

    public function showAppliedRequests()
    {
        $appliedRequests = Cache::remember('appliedRequests', 60 * 60 * 24, function(){
            return AttendanceAbsenceRequests::query()->orderByDesc('id')->paginate(15);
        });

        $teachers = Cache::remember('teachers_attendance_absence_requests', 60 * 60 * 24, function(){
            return Teacher::query()->get();
        });

        return view('admins.request_services.show_attendanceAbsence', ['appliedRequests' => $appliedRequests, 'teachers' => $teachers]);
    }

    public function showSingleAppliedRequest(AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        return view('admins.request_services.show_single_attendanceAbsence', ['attendanceAbsenceRequest' => $attendanceAbsenceRequests]);
    }

    public function assignTeacherQuery(AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {
        $class = Classes::query()
            ->select(['title', 'period'])
            ->where('class_number', '=', $attendanceAbsenceRequests->class_number)
            ->first();

        return response()->json(['attendanceAbsenceRequest' => $attendanceAbsenceRequests, 'class' => $class]);
    }

    public function assignTeacher(Request $request, AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        try {
            DB::beginTransaction();
                $teacher = Teacher::find($request->teacher_id);
                $request->available_to_date;

                ClassesTeachers::query()
                    ->where('class_number', '=', $attendanceAbsenceRequests->class_number)
                    ->where('role', '=', 'spare')
                    ->delete();

                ClassesTeachers::query()->create([
                    'class_number'  => $attendanceAbsenceRequests->class_number,
                    'teacher_email' => $teacher->email,
                    'type' => $teacher->section,
                    'role' => 'spare',
                ]);

                $attendanceAbsenceRequests->update([
                    'spare_teacher_id' => $request->teacher_id,
                    'status' => 'processing',
                    'available_to_date' => $request->available_to_date,
                    'is_overtime' => $request->overtime_checkbox == "true" ? 1 : 0,
                ]);

                // here will send mail to spare teachers

            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            dd($e);
            return response()->json(['msg' => "حدث خطأ ما"], 500);
        }

        return response()->json(['attendanceAbsenceRequest' => $attendanceAbsenceRequests]);
    }

}
