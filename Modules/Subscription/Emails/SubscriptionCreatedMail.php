<?php

namespace Modules\Subscription\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $subscription_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $subscription_id)
    {
        $this->subscription_id = $subscription_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->mailer(env('MAIL_MAILER_NO_REPLY'))
            ->from(env('MAIL_USERNAME_NO_REPLY'), 'Waheb Benmbarek')
            ->subject('Nouvelle souscription')
            ->view('emails.subscription.createTemplate', [
                'logo_url' => env('APP_URL') . '/logo.png',
                'subscription_url' => env('BACK_OFFICE_URL') . '/dashboard/coaching/subscriptions/' . $this->subscription_id
            ]);
    }
}
