<?php

namespace Modules\Food\Services\MeasurementCalculation;

use JetBrains\PhpStorm\Pure;
use Modules\DietPlan\Entities\MealFoodModel;
use Modules\Food\Entities\MeasurementModel;
use Modules\Food\Entities\RecipeIngredientModel;
use Modules\Food\Interfaces\MeasurementCalculation;
use Modules\Food\Services\FactsCalculatorService;

class RecipeIngredientMeasurementCalculatorService extends MeasurementCalculatorService
    implements MeasurementCalculation
{
    private MeasurementModel $native_ingredient_measurement;


    #[Pure] public function __construct(RecipeIngredientModel|MealFoodModel $food)
    {
        parent::__construct($food);
    }

    function performCalculation(): void
    {
        $this->calculateWeightAmount();
        $this->retrieveWeightUnit();
    }

    function measurementAsArray(): array|null
    {
        return $this->food->quantity === null ? null :
            [
                'quantity' => [
                    'amount' => $this->food->quantity,
                    'unit' => $this->food->quantity_unit
                ],
                'weight' => [
                    'amount' => $this->weight_amount,
                    'unit' => $this->weight_unit
                ],
                'facts' => (new FactsCalculatorService(
                    $this->food->quantity,
                    $this->native_ingredient_measurement->facts,
                    $this->native_ingredient_measurement->quantity,
                ))->getFacts()
            ];
    }

    private function calculateWeightAmount(): void
    {
        if ($this->food->quantity !== null) {
            $this->native_ingredient_measurement = $this->food->ingredient->measurements()->where([
                'measure_type' => $this->food->quantity_unit
            ])->first();
            if ($this->native_ingredient_measurement->weight == null)
                $this->weight_amount = null;
            else
                $this->weight_amount = ($this->food->quantity *
                        $this->native_ingredient_measurement->weight) /
                    $this->native_ingredient_measurement->quantity;
        } else
            $this->weight_amount = null;
    }

    private function retrieveWeightUnit(): void
    {
        if ($this->food->quantity !== null) {
            $this->weight_unit = $this->food->ingredient->measurements()->where([
                'measure_type' => $this->food->quantity_unit
            ])->first()->weight_unit;
        } else
            $this->weight_unit = null;
    }
}
