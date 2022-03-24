<?php

namespace Modules\Dashboard\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Subscription\Traits\Order;
use Throwable;

class SendProgressNotification extends Notification implements ShouldQueue
{
    use Queueable, Order;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     * @throws Throwable
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Cher ' . $notifiable->last_name)
            ->subject('Nouveau progrès soumi')
            ->line('L\'étudiant vient de soumettre un nouveau progrès')
            ->action('Voir le progès', env('APP_URL'). '/dashboard/coaching/classroom/students/'.$this->getUserLatestOrder()->id.'/progress')
            ->salutation('Cordialement, l\'équipe technique de WB');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            //
        ];
    }
}
