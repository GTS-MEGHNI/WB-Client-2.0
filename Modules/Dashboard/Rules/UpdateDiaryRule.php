<?php

namespace Modules\Dashboard\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;

class UpdateDiaryRule implements Rule
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
        return request()->get('order')->diaries()->find($value) !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return Responses::GENERAL_ERROR_FR;
    }
}
