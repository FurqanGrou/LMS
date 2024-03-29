<?php

namespace App\Http\Controllers\Dashboard;

use App\AttendanceAbsenceRequests;
use App\Classes;
use App\ClassesTeachers;
use App\DataTables\RequestServiceDatatable;
use App\Exports\AttendanceAbsenceExport;
use App\Form;
use App\Http\Controllers\Controller;
use App\Mail\AttendanceAbsenceRequestMail;
use App\Mail\SpareTeacherMail;
use App\Notifications\userReportMonthlyNotification;
use App\Service;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class RequestServiceController extends Controller
{

    protected static $to_mails = ['male' => 'Ibrahim.Sani@furqancenter.com', 'female' => 'salma@furqancenter.com'];
    protected static $bcc      = ['lmsfurqan1@gmail.com'];

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
        $appliedRequests = AttendanceAbsenceRequests::query()->orderByDesc('id')->paginate(15);
        $teachers = Teacher::query()->get();

        return view('admins.request_services.show_attendanceAbsence', ['appliedRequests' => $appliedRequests, 'teachers' => $teachers]);
    }

    public function exportAppliedRequests()
    {

        return Excel::download(new AttendanceAbsenceExport, 'applied-requests-attendance.xlsx');
    }

    public function showSingleAppliedRequest(AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {

        return view('admins.request_services.show_single_attendanceAbsence', ['attendanceAbsenceRequest' => $attendanceAbsenceRequests]);
    }

    // just used for get data and show it in pop-up front-end
    public function assignTeacherQuery(AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {
        $class = Classes::query()
            ->select(['title', 'period'])
            ->where('class_number', '=', $attendanceAbsenceRequests->class_number)
            ->first();

        return response()->json(['attendanceAbsenceRequest' => $attendanceAbsenceRequests, 'class' => $class]);
    }

    // assign new spare teacher, remove old spare teachers then send mail to old and new spare teachers
    public function assignTeacher(Request $request, AttendanceAbsenceRequests $attendanceAbsenceRequests)
    {
        try {
            DB::beginTransaction();
                $teacher = Teacher::find($request->teacher_id);
                $previous_teacher_spare = Teacher::find($attendanceAbsenceRequests->spare_teacher_id);

                // send email to previous spare teachers
                if ($previous_teacher_spare){

                    $subject = 'تم تغيير المعلم الاحتياط - نعتذر لكم على الأزعاج';

                    Mail::raw('نعتذر منكم، تم تغييركم من دور معلم احتياطي الخاص بحلقة رقم - ' . $attendanceAbsenceRequests->class_number,
                        function ($message) use ($subject, $previous_teacher_spare) {
                            $message->to($previous_teacher_spare->email)
                            ->cc(['attendance.permissions@furqancenter.com'])
                            ->bcc(['lmsfurqan1@gmail.com'])
                            ->subject($subject);
                    });

                }

                $class = Classes::query()
                                ->where('class_number', '=', $attendanceAbsenceRequests->class_number)
                                ->first();

                ClassesTeachers::query()->create([
                    'class_number'  => $attendanceAbsenceRequests->class_number,
                    'teacher_email' => $teacher->email,
                    'type' => $teacher->section,
                    'role' => 'spare',
                    'study_type' => $class->study_type,
                ]);

                $attendanceAbsenceRequests->update([
                    'spare_teacher_id' => $request->teacher_id,
                    'status' => 'processing',
                    'available_to_date' => $request->available_to_date,
                    'is_overtime' => $request->overtime_checkbox == "true" ? 1 : 0,
                ]);

            $teacher_spare = Teacher::find($attendanceAbsenceRequests->spare_teacher_id);

            // here will send mail to new spare teachers
            Mail::to($teacher_spare->email)
                ->cc(['attendance.permissions@furqancenter.com'])
                ->bcc('lmsfurqan1@gmail.com')
                ->send(new SpareTeacherMail($attendanceAbsenceRequests));

            DB::commit();
        }catch (\Throwable $e){
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()], 500);
        }

        return response()->json(['attendanceAbsenceRequest' => $attendanceAbsenceRequests]);
    }

}
