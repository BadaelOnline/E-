<?php

namespace App\Notifications;

use App\Models\Stores\Store;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class StoreRegistration extends Notification
{
    use Queueable;

    private $user;
    public $id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,$id)
    {
        $this->user = $user;
        $this->id = $id;
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
        $subject=sprintf('you have new request for store registration',config('app.name'),$this->user->name);
        $greeting=sprintf('hello',$notifiable->first_name);
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('please review this new store and do whats right')
                    ->action('store information', url('/api/stores/getById/'. $this->id))
                    ->line('Thank you');
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
