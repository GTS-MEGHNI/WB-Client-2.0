<?php

namespace Modules\Food\Services;

use App\Dictionary;
use Illuminate\Database\Eloquent\Collection;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Food\Entities\FoodRelevancyModel;
use Modules\Food\Entities\IngredientModel;
use Modules\Food\Entities\RecipeModel;

class FoodSearchService
{
    private array $payload;
    protected ?string $keyword;
    private mixed $ingredients = [];
    private mixed $recipes = [];

    #[ArrayShape(['ingredients' => "array|mixed", 'recipes' => "array|mixed"])]
    public function getFood(array $payload): array
    {
        $this->payload = $payload;
        $this->keyword = $this->payload['search']['keyword'];
        if ($this->keyword == null)
            return $this->getMostUsedFood();
        else {
            $this->getIngredientsByKeyword();
            $this->getRecipesByKeyword();
            return $this->mergeResults();
        }

    }

    public function getIngredientsByKeyword()
    {
        if (in_array(Dictionary::NATIVE_INGREDIENT, $this->payload['filters']['type'])) {
            $keyword = $this->keyword;
            $this->ingredients = IngredientModel::select([
                'ingredients.id', 'ingredients.name', 'food_relevancy.search_count',
                'ingredients.category'])
                ->where(function ($q) use ($keyword) {
                    $q->orWhere('name', 'like', "%$keyword%");
                    $q->orWhere('category', 'like', "%$keyword%");
                })->join('food_relevancy', 'ingredients.id', '=',
                    'food_relevancy.food_id')
                ->get();
        }
    }

    public function getRecipesByKeyword()
    {
        $keyword = $this->payload['search']['keyword'];
        if (in_array(Dictionary::NATIVE_RECIPE, $this->payload['filters']['type'])) {

            $this->recipes = RecipeModel::select([
                'recipes.id', 'recipes.name', 'food_relevancy.search_count'])
                ->join('recipes_ingredients',
                    'recipes.id', '=', 'recipes_ingredients.recipe_id')
                ->join('ingredients', 'recipes_ingredients.ingredient_id', '=', 'ingredients.id')
                ->join('food_relevancy', 'recipes.id', '=', 'food_relevancy.food_id');
            $this->recipes = $this->recipes->where(function ($q) use ($keyword) {
                $q->orWhere('recipes.name', 'like', "%$keyword%");
                $q->orWhere('recipes.description', 'like', "%$keyword%");
                $q->orWhere('ingredients.category', 'like', "%$keyword%");
                $q->orWhere('ingredients.name', 'like', "%$keyword%");
                $q->orWhere('ingredients.description', 'like', "%$keyword%");
            })->groupBy('recipes.id')
                ->get();
        }
    }

    private function mergeResults(): array
    {
        $collection = new Collection();
        $collection = $collection->merge($this->ingredients);
        $collection = $collection->merge($this->recipes);
        $collection = $collection->sortByDesc('search_count');
        $collection = $collection->take($this->payload['length']);
        return array_values($collection->toArray());
    }

    private function getMostUsedFood(): array
    {
        if (in_array(Dictionary::NATIVE_RECIPE, $this->payload['filters']['type']))
            $this->recipes = RecipeModel::select([
                'recipes.id', 'recipes.name', 'food_relevancy.search_count'])
                ->join('food_relevancy', 'recipes.id', '=', 'food_relevancy.food_id')
                ->get();

        if (in_array(Dictionary::NATIVE_INGREDIENT, $this->payload['filters']['type']))
            $this->ingredients = IngredientModel::select([
                'ingredients.id', 'ingredients.name', 'food_relevancy.search_count',
                'ingredients.category'])
                ->join('food_relevancy', 'ingredients.id', '=', 'food_relevancy.food_id')
                ->get();

        return $this->mergeResults();
    }

    public function incrementSearchCount(string $food_id)
    {
        FoodRelevancyModel::find($food_id)->increment('search_count');
    }

}
