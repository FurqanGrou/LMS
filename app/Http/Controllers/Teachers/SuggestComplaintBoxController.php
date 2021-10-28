<?php

namespace App\Http\Controllers\Teachers;


use App\Chapter;
use App\DataTables\SuggestComplaintBoxDatatable;
use App\Mail\SuggestComplaint;
use App\SuggestComplaintBox;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SuggestComplaintBoxController extends Controller
{
    public function index(SuggestComplaintBoxDatatable $suggestComplaintBoxDatatable)
    {
        return $suggestComplaintBoxDatatable->render('teachers.request_services.excuse.index');
    }

    public function create()
    {
        $students = User::all();
        $teachers = Teacher::all();
        $chapters = Chapter::all();

        return view('teachers.request_services.excuse', ['students' => $students, 'teachers' => $teachers, 'chapters' => $chapters]);
    }

    public function store(Request $request)
    {
        switch ($request->request_type){
            case "suggest":
                $email_to = ["e-supervision@furqancenter.com", "Sarah@furqangroup.com"];
                break;
            case "complaint":
                if($request->complaint_type == "officer_complaint"){
                    $email_to = ["CEO@furqangroup.com", "Sarah@furqangroup.com", "Omar@furqangroup.com"];
                }else{
                    $email_to = ["Gm@iksab.org", "Nada@furqancenter.com", "Sarah@furqangroup.com", "Omar@furqangroup.com"];
                }
                break;
            default:
                session()->flash('error', 'فشلت عملية تقديم الطلب');
                return redirect()->back();
        }

        $result = SuggestComplaintBox::create([
            'request_type' => $request->request_type,
            'complaint_type' => $request->complaint_type,
            'name' => $request->name,
            'subject' => $request->subject,
            'details' => $request->details,
            'teacher_id' => auth()->guard('teacher_web')->user()->id,
        ]);

//        Mail::to($email_to)
        Mail::to(['lmsfurqan1@gmail.com'])
            ->send(new SuggestComplaint($result->request_type . " - " . $request->subject));

        if(!empty(Mail::failures())) {
            session()->flash('error', 'فشلت عملية ارسال الطلب');
            return redirect()->back();
        }


        session()->flash('success', 'تم تقديم الطلب بنجاح');
        return redirect()->back();
    }

}
