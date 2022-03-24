<?php

namespace Modules\Authentication\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Authentication\Services\FacebookService;
use Throwable;

class FacebookAccessTokenRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws Throwable
     */
    public function passes($attribute, $value): bool
    {
        return (new FacebookService())->isValidToken($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The access token is invalid or has expired';
    }
}
