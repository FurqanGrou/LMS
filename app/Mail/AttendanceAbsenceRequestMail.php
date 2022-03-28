<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Mpdf\Tag\Sub;

class AttendanceAbsenceRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '';
        switch ($this->details->request_type){
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
        return $this->subject($subject)
            ->view('teachers.emails.request_service', ['details' => $this->details]);
    }
}
