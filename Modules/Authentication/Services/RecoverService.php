<?php

namespace Modules\Authentication\Services;

use App\Dictionary;
use App\Exceptions\IdenticalPassword;
use App\Models\User;
use App\Utility;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Emails\RecoverPasswordMail;
use Modules\Authentication\Entities\RecoverLogModel;
use Throwable;

class RecoverService
{

    private User $user;
    public string $token;


    /**
     * @param array $payload
     * @return void
     * @throws Throwable
     */
    public function sendPasscode(array $payload)
    {
        $passcode = Utility::generatePasscode();
        $this->token = ShortTermTokenService::generateRandomToken(
            Dictionary::RECOVER_PASSWORD_ACTION
        );
        RecoverLogModel::forceCreate([
            'token' => $this->token,
            'email' => $payload['email'],
            'passcode' => $passcode,
            'attempts_left' => env('PASSCODE_MAX_ATTEMPTS')
        ]);
        Mail::to($payload['email'])->send(new RecoverPasswordMail($passcode));
    }

    public function resendPasscode()
    {
        $row = RecoverLogModel::find(request()->bearerToken());
        $passcode = Utility::generatePasscode();
        $row->passcode = $passcode;
        $row->attempts_left = env('PASSCODE_MAX_ATTEMPTS');
        $row->save();
        Mail::to($row->email)->send(new RecoverPasswordMail($row->passcode));
    }


    /**
     * @throws Throwable
     */
    public function updatePassword(array $payload)
    {
        $this->user = User::where(['email' => request()->get('row')->email])->first();
        throw_if($this->samePassword($payload['password']), IdenticalPassword::class);
        $this->user->password = bcrypt($payload['password']);
        $this->user->save();
    }


    private function samePassword(string $password): bool
    {
        return Hash::check($password, $this->user->password);
    }

}
