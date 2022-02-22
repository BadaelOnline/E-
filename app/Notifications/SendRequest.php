<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendRequest extends Notification
{
    use Queueable;

    public $request;
    public $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( Request $request,$email)
    {
       $this->request= $request;
       $this->email= $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = sprintf('you have new request form', config('app.name'));
        $greeting = sprintf('Hello Front End Developer', $notifiable);
        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line('This Request body.')
            ->action('Request body',$this->request)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
