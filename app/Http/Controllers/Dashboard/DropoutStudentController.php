<?php

namespace App\Http\Controllers\Dashboard;

use App\AlertMessage;
use App\DataTables\DropoutStudentDatatable;
use App\DataTables\DropoutStudentDetailsDatatable;
use App\DropoutStudent;
use App\Notifications\AlertMessageNotification;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

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
        $request->validate([
           'student_id' => 'required|numeric|exists:users,id',
           'message_id' => 'required|numeric|exists:alert_messages,id',
        ]);

        $message = AlertMessage::query()->findOrFail($request->message_id);
        $student = User::query()->findOrFail($request->student_id);

//        Notification::route('mail', [$student->father_mail, $student->mother_mail])->notify(new AlertMessageNotification($student));
        Notification::route('mail', ['lmsfurqan1@gmail.com'])->notify(new AlertMessageNotification($student, $message));

        return ['status' => true];
    }
}
