<?php

namespace Modules\Food\Services;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class WeightCalculatorService
{
    private int|float $served_ingredient_quantity;
    private int|float $native_food_weight_amount;
    private string $native_food_weight_unit;
    private int|float|null $native_food_quantity;

    public function __construct(null|string    $served_ingredient_quantity,
                                int|float|null $native_food_weight_amount,
                                string|null    $native_food_weight_unit,
                                string|null    $native_food_quantity)
    {
        $this->served_ingredient_quantity = $served_ingredient_quantity;
        $this->native_food_weight_amount = $native_food_weight_amount;
        $this->native_food_weight_unit = $native_food_weight_unit;
        $this->native_food_quantity = $native_food_quantity;
    }

    #[Pure] #[ArrayShape(['amount' => "float|int", 'unit' => "string"])]
    public function getWeight(): array
    {
        return [
            'amount' => $this->getWeightAmount(),
            'unit' => $this->getWeightUnit()
        ];
    }

    #[Pure] private function getWeightAmount(): int|float|null
    {
        if ($this->served_ingredient_quantity == null | $this->native_food_weight_amount == null
            | $this->native_food_quantity)
            return null;
        else
            return ($this->served_ingredient_quantity *
                    $this->native_food_weight_amount) /
                $this->native_food_quantity;
    }


    private function getWeightUnit(): ?string
    {
        return $this->native_food_weight_unit;
    }
}

