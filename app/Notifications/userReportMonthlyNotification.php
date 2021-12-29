<?php

namespace App\Notifications;

use Carbon\Carbon;
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
        $month = substr(request()->date_filter, -2);
        $year = substr(request()->date_filter, 0, 4);
        $month_name = Carbon::createFromDate($year, $month)->getTranslatedMonthName();

        $subject = " نتيجة شهر $month_name " . date("Y") . " - الطالب/ة -  $this->student_name  ،  $this->student_number";

        return (new MailMessage)->subject($subject)->view('emails.user.monthly_report', ['student' => $this->student, 'student_id' => $this->student_id, "student_name" => $this->student_name, 'date_filter' => $this->date_filter, 'month_name' => $month_name, 'month' => $month]);
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
