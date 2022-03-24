<?php

namespace Modules\Subscription\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use App\Dictionary;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class WeightGoalRule implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws ContainerExceptionInterface
     */
    public function passes($attribute, $value): bool
    {
        try {
            if (request()->get('diet')['program'] == Dictionary::WEIGHT_GAIN_DIET) {
                if($value < request()->get('body')['weight'])
                    return false;
            }
        } catch (NotFoundExceptionInterface) {
            return false;
        }
        return true;
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
