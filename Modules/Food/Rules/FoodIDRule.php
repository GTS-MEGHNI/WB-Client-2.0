<?php

namespace Modules\Food\Rules;

use App\Dictionary;
use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use Modules\Food\Entities\IngredientModel;
use Modules\Food\Entities\RecipeModel;
use Modules\Food\Traits\Food;

class FoodIDRule implements Rule
{
    use Food;
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
        $product = match ($this->getFoodTypeByID($value)) {
            Dictionary::RECIPE => RecipeModel::find($value),
            Dictionary::INGREDIENT => IngredientModel::find($value),
        };
        if($product == null)
            return false;
        request()->request->add(['food' => $product]);
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return Responses::FOOD_NOT_FOUND;
    }
}
