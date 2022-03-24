<?php

namespace Modules\Authentication\Emails;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivateAccountMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $passcode;
    public string $name;
    public string $sentence;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $passcode, string $name)
    {
        $this->passcode = $passcode;
        $this->name = $name;
        $this->sentence = 'Veuillez utiliser le code ci-dessous pour confirmer votre inscription :';
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
            ->subject('Activation du compte')
            ->view('emails.activation.template', [
                'passcode' => $this->passcode,
                'name' => $this->name,
                'app_name' => env('APP_NAME'),
                'year' => Carbon::now()->year,
                'ttl' => intval(env('VERIFICATION_TOKEN_TTL')) / 60,
                'sentence' => $this->sentence
            ]);
    }
}
