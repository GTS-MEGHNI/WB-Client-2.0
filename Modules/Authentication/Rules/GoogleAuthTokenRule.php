<?php

namespace Modules\Authentication\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Authentication\Services\GoogleService;

class GoogleAuthTokenRule implements Rule
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
        return (new GoogleService())->isValidToken($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The authentication token is invalid or has expired.';
    }
}
