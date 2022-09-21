<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Mpdf\Tag\Sub;

class AttendanceAbsenceRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $options;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        if ($this->options['type'] == 'remove') {
//            $subject = 'تم تغيير المعلم الاحتياط - نعتذر لكم على الأزعاج';
//
//            Mail::raw('نعتذر منكم، تم تغييركم من دور معلم احتياطي الخاص بحلقة رقم - ' . $this->options['details']->class_number,
//                function ($message) use ($subject) {
//                $message->to('')
//                ->subject($subject);
//            });
//
//            return $this->subject($subject)
//                ->html();
        }else{
            $subject = '';
            switch ($this->options->request_type){
                case 'absence':
                    $subject = 'طلب - اذن غياب';
                    break;
                case 'delay':
                    $subject = 'طلب - اذن تأخير';
                    break;
                case 'exit':
                    $subject = 'طلب - اذن خروج';
                    break;
            }
        }

        return $this->subject($subject)
            ->view('teachers.emails.request_service', ['details' => $this->options['details']]);
    }
}
