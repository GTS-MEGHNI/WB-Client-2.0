<?php

namespace Modules\Food\Services\Recipe;

use Modules\Food\Entities\RecipeModel;
use Modules\Food\Services\FoodSearchService;

class RecipeService
{
    protected array $payload;

    public function get(array $payload): array
    {
        (new FoodSearchService())->incrementSearchCount($payload['id']);
        return RecipeModel::find($payload['id'])->details();
    }


}
