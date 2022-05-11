<?php

namespace Modules\Food\Services;

use App\Dictionary;
use Modules\Food\Entities\IngredientModel;
use Modules\Food\Entities\RecipeModel;
use Modules\Food\Traits\Food;
use Throwable;

class FoodService
{
    use Food;

    /**
     * @return array
     * @throws Throwable
     */
    public function get(): array
    {
        $food = match ($this->getFoodTypeByID(request()->route('foodId'))) {
            Dictionary::RECIPE => RecipeModel::find(request()->route('foodId')),
            Dictionary::INGREDIENT => IngredientModel::find(request()->route('foodId')),
        };
        return $food->details();
    }

}
