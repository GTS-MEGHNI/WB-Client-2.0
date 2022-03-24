<?php

namespace Modules\Food\Traits;

use App\Dictionary;


trait Food
{
    private function getFolderByFoodID(string $food_id): string
    {
        return explode('_', $food_id)[0] . 's';
    }

    private function getServedFoodType(string $food_id): string
    {
        return match ($this->getFoodTypeByID($food_id)) {
            Dictionary::RECIPE => Dictionary::SERVED_RECIPE,
            Dictionary::INGREDIENT => Dictionary::NATIVE_INGREDIENT
        };
    }

    private function getFoodTypeByID(string $food_id): string
    {
        return explode('_', $food_id)[0];
    }
}
