<?php

namespace Modules\DietPlan\Services\FoodConsumption;

use App\Dictionary;
use Modules\DietPlan\Entities\MealFoodModel;
use Modules\DietPlan\Interfaces\FoodConsumption;
use Modules\Food\Entities\FactModel;
use Modules\Food\Services\FactsCalculatorService;
use Modules\Food\Traits\Food;

class FoodConsumptionService extends ConsumptionService implements FoodConsumption
{
    use Food;

    private MealFoodModel $meal;
    private mixed $native_food_measurement;
    private mixed $served_food_measurement;
    private FactsCalculatorService $facts_calculator;

    public function __construct(MealFoodModel $meal)
    {
        $this->meal = $meal;
    }

    public function getConsumptions()
    {
        FactModel::unguard();
        $facts = $this->meal->measurement->facts;
        FactModel::reguard();
        $this->total_fat = $facts->fat;
        $this->total_protein = $facts->protein;
        $this->total_carbs = $facts->carbs;
        $this->total_calories = $facts->calories;
        if ($this->meal->consumed === true) {
            $this->consumed_fat = $facts->fat;
            $this->consumed_protein = $facts->protein;
            $this->consumed_carbs = $facts->carbs;
            $this->consumed_calories = $facts->calories;
        }
    }

    function calculate(): void
    {
        $this->calculateFacts();
        $this->total_fat = $this->facts_calculator->fat_amount;
        $this->total_protein = $this->facts_calculator->protein_amount;
        $this->total_carbs = $this->facts_calculator->carbs_amount;
        $this->total_calories = $this->facts_calculator->calories_amount;
        if($this->meal->consumed === true) {
            $this->consumed_fat = $this->facts_calculator->fat_amount;
            $this->consumed_protein = $this->facts_calculator->protein_amount;
            $this->consumed_carbs = $this->facts_calculator->carbs_amount;
            $this->consumed_calories = $this->facts_calculator->calories_amount;
        }
    }

    private function calculateFacts() {
        $this->facts_calculator = new FactsCalculatorService(
            $this->getServedFoodQuantity(),
            $this->getNativeFoodFacts(),
            $this->getNativeFoodQuantity()
        );
    }

    private function getServedFoodQuantity(): mixed
    {
        return $this->meal->measurement->quantity;
    }

    private function getNativeFoodQuantity(): mixed
    {
        return match ($this->getFoodTypeByID($this->meal->native_food_id)) {
            Dictionary::INGREDIENT => $this->meal->ingredient->measurements()->where([
                'measure_type' => $this->meal->measurement->measure_type
            ])->first()->quantity,
            Dictionary::RECIPE => $this->meal->recipe->measurements()->where([
                'measure_type' => $this->meal->measurement->measure_type
            ])->first()->quantity,
            default => 0,
        };
    }

    private function getNativeFoodFacts(): FactModel
    {
        return match ($this->getFoodTypeByID($this->meal->native_food_id)) {
            Dictionary::RECIPE => $this->meal->recipe->measurements()->where([
                'measure_type' => $this->meal->measurement->measure_type
            ])->first()->facts,
            Dictionary::INGREDIENT => $this->meal->ingredient->measurements()->where([
                'measure_type' => $this->meal->measurement->measure_type
            ])->first()->facts
        };
    }


}
