<?php

namespace Modules\Authentication\Services;

use App\Dictionary;
use App\Utility;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Emails\ActivateAccountMail;
use Modules\Authentication\Emails\RecoverPasswordMail;
use Modules\Authentication\Emails\VerifyAccountMail;
use Modules\Authentication\Entities\RecoverLogModel;
use Modules\Authentication\Entities\RegisterLog;
use Throwable;

class PasscodeService
{
    public string $token;
    private mixed $row;

    /**
     * @param array $payload
     * @throws Throwable
     */
    public function sendActivationPasscode(array $payload)
    {
        $this->token = (new ShortTermTokenService())->generateRandomToken(Dictionary::ACCOUNT_ACTIVATION_ACTION);
        $passcode = Utility::generatePasscode();
        $row = RegisterLog::forceCreate([
            'token' => $this->token,
            'email' => $payload['email'],
            'first_name' => $payload['firstname'],
            'last_name' => $payload['lastname'],
            'password' => $payload['password'],
            'passcode' => $passcode,
            'attempts_left' => env('PASSCODE_MAX_ATTEMPTS')
        ]);
        Mail::to($payload['email'])->send(new ActivateAccountMail($passcode, $row->first_name));
    }

    /**
     *
     */
    public function resendActivationPasscode()
    {
        $this->row = RegisterLog::find(request()->bearerToken());
        $this->updateRow();
        Mail::to($this->row->email)->send(new ActivateAccountMail(
            $this->row->passcode, $this->row->first_name)
        );
    }

    /**
     * @param array $payload
     * @return string|void
     * @throws Throwable
     */
    public function sendRecoverPasscode(array $payload)
    {
        $passcode = Utility::generatePasscode();
        if (!Arr::has($payload, 'row')) {
            $token = ShortTermTokenService::generateRandomToken(Dictionary::RECOVER_PASSWORD_ACTION);
            RecoverLogModel::where(['email' => $payload['email']])->delete();
            RecoverLogModel::forceCreate([
                'token' => $token,
                'email' => $payload['email'],
                'passcode' => $passcode,
                'attempts_left' => env('PASSCODE_MAX_ATTEMPTS')
            ]);
            Mail::to($payload['email'])->send(new RecoverPasswordMail($passcode));
            return $token;
        } else {
            $row = RecoverLogModel::find($payload['token']);
            $row->passcode = $passcode;
            $row->save();
            Mail::to($payload['email'])->send(new RecoverPasswordMail($passcode));
        }
    }

    public function resendRecoverPasscode() {
        $this->row = RecoverLogModel::find(request()->bearerToken());
        $this->updateRow();
        Mail::to($this->row->email)->send(new RecoverPasswordMail($this->row->passcode));
    }

    /**
     * @param array $payload
     * @param bool $user_found
     * @throws Throwable
     */
    public function sendVerificationPasscode(array $payload, bool $user_found) {
        $this->token = (new ShortTermTokenService())->generateRandomToken(Dictionary::ACCOUNT_ACTIVATION_ACTION);
        $passcode = Utility::generatePasscode();
        $row = RegisterLog::forceCreate([
            'token' => $this->token,
            'email' => $payload['email'],
            'first_name' => $payload['firstname'],
            'last_name' => $payload['lastname'],
            'passcode' => $passcode,
            'attempts_left' => env('PASSCODE_MAX_ATTEMPTS')
        ]);
        if(!$user_found)
            Mail::to($payload['email'])->send(new ActivateAccountMail($passcode, $row->first_name));
        else
            Mail::to($payload['email'])->send(new VerifyAccountMail($passcode, $row->first_name));
    }

    private function updateRow() {
        $passcode = Utility::generatePasscode();
        $this->row->passcode = $passcode;
        $this->row->attempts_left = env('PASSCODE_MAX_ATTEMPTS');
        $this->row->save();
    }



}
