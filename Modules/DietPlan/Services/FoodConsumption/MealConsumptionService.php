<?php

namespace Modules\DietPlan\Services\FoodConsumption;

use JetBrains\PhpStorm\ArrayShape;
use Modules\DietPlan\Entities\DayMealModel;
use Modules\DietPlan\Interfaces\FoodConsumption;

class MealConsumptionService extends ConsumptionService implements FoodConsumption
{
    private DayMealModel $collation;

    public function __construct(DayMealModel $collation)
    {
        $this->collation = $collation;
    }

    #[ArrayShape(['calories' => "array[]", 'protein' => "\array[][]", 'carbs' => "\array[][]", 'fat' => "\array[][]"])]
    public function getConsumption() : array {
        $this->calculate();
        return $this->getConsumptionsAsArray();
    }

    function calculate(): void
    {
        $meals = $this->collation->foods;
        foreach ($meals as $meal) {
            $meal_consumption = new FoodConsumptionService($meal);
            $meal_consumption->calculate();
            $this->sum($meal_consumption);
        }
    }
}
