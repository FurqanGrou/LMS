<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertMessageNotification extends Notification
{
    use Queueable;

    public $student;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($student, $message)
    {
        $this->student = $student;
        $this->message = $message;
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
        $subject = "تنبيه إنقطاع عن حضور فصول مجموعة الفرقان | Absence from attending Furqan Group sessions";

        return (new MailMessage)
            ->subject($subject)
//            ->cc(['admission@furqangroup.com', 'thamer@furqangroup.com', 'wisam.morsi@furqangroup.com'])
            ->view('emails.admin.dropout_students', ['student' => $this->student, 'message_content' => $this->message]);
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
