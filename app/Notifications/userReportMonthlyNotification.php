<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userReportMonthlyNotification extends Notification
{
    use Queueable;

    public $monthly_report;
    public $student_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($monthly_report,$student_id)
    {
        $this->monthly_report=$monthly_report;
        $this->student_id=$student_id;
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
        return (new MailMessage)->view('emails.user.monthly_report', ['monthly_report' => $this->monthly_report,'student_id'=>$this->student_id]);
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
