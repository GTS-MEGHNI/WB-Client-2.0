<?php

namespace Modules\Food\Services;

use JetBrains\PhpStorm\ArrayShape;

class FactsCalculatorService
{
    private mixed $native_ingredient_facts;
    private int|float $served_food_quantity;
    private int|float $native_food_quantity;
    public int|float $protein_amount;
    public int|float $carbs_amount;
    public int|float $fat_amount;
    public int|float $calories_amount;

    public function __construct(string $served_food_quantity, mixed $native_ingredient_facts,
                                mixed  $native_food_quantity)
    {
        $this->served_food_quantity = $served_food_quantity;
        $this->native_ingredient_facts = $native_ingredient_facts;
        $this->native_food_quantity = $native_food_quantity;
        $this->calculate();
    }

    #[ArrayShape(['calories' => "array", 'protein' => "array[]", 'carbs' => "array[]", 'fat' => "array[]"])]
    public function getFacts(): array
    {
        return [
            'calories' => [
                'amount' => $this->calories_amount,
                'unit' => 'kcal'
            ],
            'protein' => [
                'weight' => [
                    'amount' => $this->protein_amount,
                    'unit' => 'g'
                ],
                'energy' => [
                    'amount' => $this->protein_amount * 4,
                    'unit' => 'kcal'
                ]
            ],
            'carbs' => [
                'weight' => [
                    'amount' => $this->carbs_amount,
                    'unit' => 'g'
                ],
                'energy' => [
                    'amount' => $this->carbs_amount * 4,
                    'unit' => 'kcal'
                ]
            ],
            'fat' => [
                'weight' => [
                    'amount' => $this->fat_amount,
                    'unit' => 'g'
                ],
                'energy' => [
                    'amount' => $this->fat_amount * 9,
                    'unit' => 'kcal'
                ]
            ]
        ];
    }

    public function calculate()
    {
        $this->protein_amount = $this->getAmount('protein');
        $this->carbs_amount = $this->getAmount('carbs');
        $this->calories_amount = $this->getAmount('calories');
        $this->fat_amount = $this->getAmount('fat');
    }

    private function getAmount(string $type): int|float
    {
        $amount = ($this->served_food_quantity *
                $this->native_ingredient_facts->{$type}) /
            $this->native_food_quantity;

        switch ($type) {
            case 'carbs':
                $this->carbs_amount = $amount;
                break;
            case 'fat':
                $this->fat_amount = $amount;
                break;
            case 'protein':
                $this->protein_amount = $amount;
                break;
        }
        return $amount;
    }


}
