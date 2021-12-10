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
    public $date_filter;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student, $date_filter)
    {
        $this->student        = $student;
        $this->student_id     = $student->id;
        $this->student_name   = $student->name;
        $this->student_number = $student->student_number;
        $this->date_filter = $date_filter;
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
        $month = substr($this->date_filter, -2);
        $year = substr($this->date_filter, 0, 4);
        $subject = "نتيجة شهر ". " $month " . $year ." - الطالب/ة - " . $this->student_name . " ، " . $this->student_number . "";
        return (new MailMessage)->subject($subject)->view('emails.user.monthly_report', ['student' => $this->student, 'student_id' => $this->student_id, "student_name" => $this->student_name, 'date_filter' => $this->date_filter]);
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
