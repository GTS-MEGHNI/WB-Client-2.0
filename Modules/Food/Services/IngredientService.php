<?php

namespace Modules\Food\Services;

use App\Dictionary;
use Illuminate\Support\Str;
use Modules\Food\Entities\IngredientModel;

class IngredientService
{

    public function create(array $payload) {
        IngredientModel::forceCreate([
            'food_id' => Dictionary::INGREDIENT.'_'.Str::uuid(),
            'name' => $payload['name'],
            'category' => $payload['category'],
            'description' => $payload['description']
        ]);
    }

    public function get(array $payload) {
        (new FoodSearchService())->incrementSearchCount($payload['id']);
        return IngredientModel::find($payload['id'])->details();
    }

}
