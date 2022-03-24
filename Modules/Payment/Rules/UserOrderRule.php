<?php

namespace Modules\Payment\Rules;

use App\Utility;
use Illuminate\Contracts\Validation\Rule;
use Modules\Subscription\Entities\SubscriptionModel;

class UserOrderRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $order = SubscriptionModel::where([
            'user_id' => Utility::getUserId(),
            'order_id' => $value
        ])->first();
        return $order != null;
    }

    /**
     * Get the validation error message.
     *
     */
    public function message()
    {

    }
}
