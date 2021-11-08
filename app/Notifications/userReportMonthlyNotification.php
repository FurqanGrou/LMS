<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userReportMonthlyNotification extends Notification
{
    use Queueable;

    public $student;
    public $student_id;
    public $student_name;
    public $student_number;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student)
    {
        $this->student        = $student;
        $this->student_id     = $student->id;
        $this->student_name   = $student->name;
        $this->student_number = $student->student_number;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = "نتيجة شهر ". " اكتوبر " . date("Y") ." - الطالب/ة - " . $this->student_name . " ، " . $this->student_number . "";
        return (new MailMessage)->subject($subject)->view('emails.user.monthly_report', ['student' => $this->student, 'student_id' => $this->student_id, "student_name" => $this->student_name]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
