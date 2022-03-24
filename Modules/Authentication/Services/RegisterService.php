<?php

namespace Modules\Authentication\Services;

use App\Dictionary;
use App\Models\User;
use App\Utility;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Emails\ActivateAccountMail;
use Modules\Authentication\Entities\RegisterLog;
use Throwable;

class RegisterService extends AuthService
{

    public string $token;

    /**
     * @throws Throwable
     */
    public function sendPasscode(array $payload) {
        $this->token = (new ShortTermTokenService())->generateRandomToken(
            Dictionary::ACCOUNT_ACTIVATION_ACTION);
        $passcode = Utility::generatePasscode();
        $row = RegisterLog::forceCreate([
            'token' => $this->token,
            'email' => $payload['email'],
            'first_name' => $payload['firstname'],
            'last_name' => $payload['lastname'],
            'password' => Arr::has($payload, 'password') ?
                bcrypt($payload['password']) : null,
            'passcode' => $passcode,
            'attempts_left' => env('PASSCODE_MAX_ATTEMPTS')
        ]);
        Mail::to($payload['email'])->send(new ActivateAccountMail($passcode, $row->first_name));
    }

    public function record() {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->user = User::addNewUser(request()->request->get('row')->toArray());
    }

    public function resendPasscode() {
        $row = RegisterLog::find(request()->bearerToken());
        $passcode = Utility::generatePasscode();
        $row->passcode = $passcode;
        $row->attempts_left = env('PASSCODE_MAX_ATTEMPTS');
        $row->save();
        Mail::to($row->email)->send(new ActivateAccountMail($row->passcode, $row->first_name));
    }



}
