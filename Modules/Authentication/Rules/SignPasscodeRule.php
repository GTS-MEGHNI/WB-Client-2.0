<?php

namespace Modules\Authentication\Rules;

use App\Responses;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Modules\Authentication\Entities\RegisterLog;
use Modules\Authentication\Services\RegisterService;

class SignPasscodeRule implements Rule
{

    private string $message;


    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $row = RegisterLog::find(request()->bearerToken());

        if ($this->passcodeExpired($row)) {
            $this->message = Responses::SESSION_EXPIRED;
            return false;
        }

        if ($row->passcode !== $value) {
            $row->decrement('attempts_left');
            if ($row->attempts_left === 0) {
                (new RegisterService())->resendPasscode();
                $this->message = Responses::PASSCODE_OVERFLOW;
            } else
                $this->message = Responses::WRONG_PASSCODE;
            return false;
        }

        $row->verified = 1;
        $row->save();
        request()->request->add(['row' => $row]);
        /* purge row  */
        if (request()->route()->getName() != 'quick-auth-challenge')
            RegisterLog::find(request()->bearerToken())->delete();
        return true;
    }

    private function passcodeExpired(RegisterLog $row): bool
    {
        return Carbon::now()->subSeconds(env('VERIFICATION_TOKEN_TTL'))
            ->greaterThan(Carbon::parse($row->created_at));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
