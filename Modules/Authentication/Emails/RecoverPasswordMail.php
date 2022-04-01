<?php

namespace Modules\Authentication\Emails;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecoverPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $passcode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $passcode)
    {
        $this->passcode = $passcode;
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
            ->from(env('MAIL_FROM_ADDRESS_NO_REPLY'), 'Waheb Benmbarek')
            ->subject('Demande de changement de mot de passe')
            ->view('emails.recover.template', [
                'passcode' => $this->passcode,
                'app_name' => env('APP_NAME'),
                'year' => Carbon::now()->year,
                'logo_url' => env('APP_URL') . '/logo.png'
            ]);
    }
}
