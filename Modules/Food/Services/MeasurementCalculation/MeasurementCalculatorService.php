<?php

namespace Modules\Food\Services\MeasurementCalculation;

use Modules\DietPlan\Entities\MealFoodModel;
use Modules\Food\Entities\RecipeIngredientModel;

class MeasurementCalculatorService
{
    /**
     * This class will be used for recipe ingredient and served ingredient / recipe
     */

    protected MealFoodModel|RecipeIngredientModel $food;
    protected int|float|null $weight_amount;
    protected ?string $weight_unit;
    protected int|float $quantity_amount;
    protected int|float $quantity_unit;


    public function __construct(MealFoodModel|RecipeIngredientModel $food)
    {
        $this->food = $food;
    }

    public function getMeasurement(): array|null
    {
        if ($this->food instanceof MealFoodModel)
            $measurement_calculator = (new ServedFoodMeasurementCalculatorService($this->food));
        else
            $measurement_calculator = (new RecipeIngredientMeasurementCalculatorService($this->food));

        $measurement_calculator->performCalculation();
        return $measurement_calculator->measurementAsArray();
    }

}

