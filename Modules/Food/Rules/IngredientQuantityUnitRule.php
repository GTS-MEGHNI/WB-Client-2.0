<?php

namespace Modules\Food\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use Modules\Food\Entities\IngredientModel;
use Throwable;

class IngredientQuantityUnitRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private string $message;
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @throws Throwable
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $index = filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
        $ingredient_id = request()->get('ingredients')[$index]['id'];
        $basic_measurement_row = IngredientModel::where(['food_id' => $ingredient_id])
            ->first()
            ->measurements()
            ->where(['measure_type' => $value])
            ->first();

        if($basic_measurement_row == null) {
            $this->message = Responses::BASIC_MEASUREMENT_NOT_FOUND;
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
        return $this->message;
    }
}
