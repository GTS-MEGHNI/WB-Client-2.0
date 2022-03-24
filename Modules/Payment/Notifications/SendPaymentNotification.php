<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $order_id;
    public string $payment_method;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $order_id, string $payment_method)
    {
        $this->order_id = $order_id;
        $this->payment_method = $payment_method;
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
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Cher ' . $notifiable->last_name)
            ->subject('Paiment soumis')
            ->line('Le paiment concernant la souscription N° ' . $this->order_id . '
            vient d\'être soumi en utilisant ' . $this->payment_method)
            ->salutation('Cordialement, l\'équipe technique de WB');
    }
}
