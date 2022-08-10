<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestServiceExcuseNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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

        $subjects = [
            'absence' => 'طلب اذن غياب معلم عن حلقة',
            'delay' => 'طلب اذن تأخير معلم عن حلقة',
            'exit' => 'طلب اذن خروج معلم من حلقة',
        ];

        $view_name = 'emails.admin.excuse_teachers_' . $this->data['type'];

        return (new MailMessage)
            ->subject($subjects[$this->data['type']])
//            ->cc(['admission@furqangroup.com', 'thamer@furqangroup.com', 'wisam.morsi@furqangroup.com'])
            ->view($view_name, ['data' => $this->data]);
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
