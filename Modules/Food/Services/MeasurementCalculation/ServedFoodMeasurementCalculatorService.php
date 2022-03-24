<?php

namespace Modules\Food\Services\MeasurementCalculation;

use App\Dictionary;
use App\Utility;
use JetBrains\PhpStorm\Pure;
use Modules\DietPlan\Entities\MealFoodModel;
use Modules\Food\Entities\MeasurementModel;
use Modules\Food\Entities\RecipeIngredientModel;
use Modules\Food\Interfaces\MeasurementCalculation;
use Modules\Food\Services\FactsCalculatorService;
use Modules\Food\Traits\Food;

class ServedFoodMeasurementCalculatorService extends MeasurementCalculatorService
    implements MeasurementCalculation
{

    use Food;

    private MeasurementModel $native_food_measurement;


    #[Pure] public function __construct(RecipeIngredientModel|MealFoodModel $food)
    {
        parent::__construct($food);
    }

    function performCalculation(): void
    {
        $this->getNativeFoodMeasurement();
        $this->getWeightAmount();
    }

    function measurementAsArray(): array
    {
        return Utility::remove_array_shape_tag([
                'quantity' => [
                    'amount' => $this->food->measurement->quantity,
                    'unit' => $this->food->measurement->measure_type
                ],
                'weight' => [
                    'amount' => $this->weight_amount,
                    'unit' => $this->native_food_measurement->weight_unit
                ],
                'facts' => (new FactsCalculatorService(
                    $this->food->measurement->quantity,
                    $this->native_food_measurement->facts,
                    $this->native_food_measurement->quantity,
                ))->getFacts()
            ]);
    }

    private function getWeightAmount(): void
    {
        $this->weight_amount = ($this->food->measurement->quantity *
                $this->native_food_measurement->weight) /
            $this->native_food_measurement->quantity;
    }

    private function getNativeFoodMeasurement(): void
    {
        $this->native_food_measurement = match ($this->getFoodTypeByID($this->food->native_food_id)) {
            Dictionary::RECIPE => $this->food->recipe->measurements()->where([
                'measure_type' => $this->food->measurement->measure_type
            ])->first(),
            Dictionary::INGREDIENT => $this->food->ingredient->measurements()->where([
                'measure_type' => $this->food->measurement->measure_type
            ])->first()
        };
    }


}
