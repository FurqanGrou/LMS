<?php

namespace App;

use App\Mail\AttendanceAbsenceRequestMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class AttendanceAbsenceRequests extends Model
{
    protected $guarded = [];
    protected static $to_mails = ['male' => 'Ibrahim.Sani@furqancenter.com', 'female' => 'salma@furqancenter.com'];
    protected static $bcc      = ['lmsfurqan1@gmail.com'];
    protected $appends = ['type', 'status_title'];

    protected static function booted()
    {
        static::created(function(AttendanceAbsenceRequests $absenceRequests) {

            $supervisor_emails = ClassesTeachers::query()
                ->where('role', '=', 'supervisor')
                ->where('class_number', '=', $absenceRequests->class_number)
                ->distinct()
                ->pluck('teacher_email')
                ->toArray();

//            Mail::to($supervisor_emails)
            Mail::to(['lmsfurqan1@gmail.com'])
//                ->cc([self::$to_mails[$absenceRequests->teacher->section]])
                ->bcc(self::$bcc)
                ->send(new AttendanceAbsenceRequestMail($absenceRequests));

            Cache::forget('appliedRequests');
        });

        static::updated(function(AttendanceAbsenceRequests $absenceRequests) {

            $supervisor_emails = ClassesTeachers::query()
                ->where('role', '=', 'supervisor')
                ->where('class_number', '=', $absenceRequests->class_number)
                ->distinct()
                ->pluck('teacher_email')
                ->toArray();

//            Mail::to($supervisor_emails)
            Mail::to(['lmsfurqan1@gmail.com'])
//                ->cc([self::$to_mails[$absenceRequests->teacher->section]])
                ->bcc(self::$bcc)
                ->send(new AttendanceAbsenceRequestMail($absenceRequests));

            Cache::forget('appliedRequests');
        });

    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function spareTeacher()
    {
        return $this->belongsTo(Teacher::class, 'spare_teacher_id');
    }

    public function getTypeAttribute()
    {
        $type = '';
        switch ($this->request_type){
            case 'absence':
                $type = 'اذن غياب';
                break;
            case 'delay':
                $type = 'اذن تأخير';
                break;
            case 'exit':
                $type = 'اذن خروج';
                break;
        }

        return $type;
    }

    public function getStatusTitleAttribute()
    {
        $status_title = '';
        switch ($this->status){
            case 'pending':
                $status_title = 'جديد';
                break;
            case 'processing':
                $status_title = 'تمت المعالجة';
                break;
            case 'completed':
                $status_title = 'انتهى';
                break;
        }

        return $status_title;
    }

    public function classNumber()
    {
        return $this->belongsTo(Classes::class, 'class_number', 'class_number');
    }

}
