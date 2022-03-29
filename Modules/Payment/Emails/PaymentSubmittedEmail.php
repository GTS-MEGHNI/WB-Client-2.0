<?php /** @noinspection SpellCheckingInspection */

namespace Modules\Payment\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSubmittedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subscription_id;
    public string $payment_method;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $subscription_id, string $payment_method)
    {
        $this->subscription_id = $subscription_id;
        $this->payment_method = $payment_method;
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
            ->from(env('MAIL_USERNAME_NO_REPLY'))
            ->subject('Paiement soumis')
            ->view('emails.subscription.paymentTemplate', [
                'logo_url' => env('APP_URL') . '/logo.png',
                'subscription_id' => $this->subscription_id,
                'payment_method' => $this->payment_method,
                'subscription_url' => env('BACK_OFFICE_URL') . '/dashboard/coaching/subscriptions/' . $this->subscription_id
            ]);
    }
}
