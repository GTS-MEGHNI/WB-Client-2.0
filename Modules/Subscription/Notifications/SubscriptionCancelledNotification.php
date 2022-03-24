<?php

namespace Modules\Subscription\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $order_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Get the notification's delivery channels.
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
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Cher ' . $notifiable->last_name)
            ->subject('Souscription annulée')
            ->line('La souscription ' . $this->order_id . ' vient d\'être annulée')
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
