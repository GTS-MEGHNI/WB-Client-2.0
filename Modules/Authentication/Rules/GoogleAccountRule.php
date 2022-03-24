<?php

namespace Modules\Authentication\Rules;

use App\Models\User;
use App\Responses;
use Illuminate\Contracts\Validation\Rule;

class GoogleAccountRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $user = User::where('email', '=', $value)->first();
        return $user == null || !$user->googleAccount($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return Responses::USING_GOOGLE_ACCOUNT;
    }
}
