<?php

namespace Modules\Subscription\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use App\Dictionary;

class CheckoutPlanRule implements Rule
{
    private ?array $meta;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if($this->meta === null || !Arr::exists($this->meta, 'program'))
            return false;

        $diet = $this->meta['program'];

        /*if ($diet == Dictionary::WEIGHT_GAIN_DIET && $value == Dictionary::ONE_MONTH_SUBSCRIPTION)
            return false;*/

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The validation error message.';
    }
}
