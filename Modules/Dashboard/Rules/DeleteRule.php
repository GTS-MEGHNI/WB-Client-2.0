<?php

namespace Modules\Dashboard\Rules;

use App\Responses;
use App\Utility;
use Illuminate\Contracts\Validation\Rule;
use App\Dictionary;
use Modules\Subscription\Entities\SubscriptionModel;

class DeleteRule implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $order = SubscriptionModel::where([
            'user_id' => Utility::getUserId(),
            'order_id' => $value
        ])->first();

        if($order == null || !in_array($order->status,
                [Dictionary::CANCELED_ORDER, Dictionary::REJECTED_ORDER]))
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return Responses::CANNOT_DELETE;
    }
}
