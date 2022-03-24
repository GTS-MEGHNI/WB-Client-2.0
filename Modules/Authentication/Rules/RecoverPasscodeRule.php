<?php

namespace Modules\Authentication\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use Modules\Authentication\Entities\RecoverLogModel;
use Modules\Authentication\Services\PasscodeService;
use Modules\Authentication\Services\RecoverService;
use Throwable;


class RecoverPasscodeRule implements Rule
{
    private string $message;


    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws Throwable
     */
    public function passes($attribute, $value): bool
    {
        $row = RecoverLogModel::find(request()->bearerToken());
        if($row->attempts_left === 0) {
            (new RecoverService())->resendPasscode();
            $this->message = Responses::PASSCODE_OVERFLOW;
        } else if ($row->passcode !== $value) {
            $row->decrement('attempts_left');
            $this->message = Responses::WRONG_PASSCODE;
        } else {
            $row->verified = 1;
            $row->save();
            return true;
        }
        return false;
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
