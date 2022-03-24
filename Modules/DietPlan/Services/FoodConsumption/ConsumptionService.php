<?php

namespace Modules\DietPlan\Services\FoodConsumption;

use JetBrains\PhpStorm\ArrayShape;

class ConsumptionService
{
    public int|float $total_calories = 0;
    public int|float $consumed_calories = 0;
    public int|float $total_protein = 0;
    public int|float $consumed_protein = 0;
    public int|float $total_carbs = 0;
    public int|float $consumed_carbs = 0;
    public int|float $total_fat = 0;
    public int|float $consumed_fat = 0;

    protected function sum(mixed $consumption)
    {
        $this->consumed_calories += $consumption->consumed_calories;
        $this->total_calories += $consumption->total_calories;
        $this->consumed_carbs += $consumption->consumed_carbs;
        $this->total_carbs += $consumption->total_carbs;
        $this->consumed_protein += $consumption->consumed_protein;
        $this->total_protein += $consumption->total_protein;
        $this->consumed_fat += $consumption->consumed_fat;
        $this->total_fat += $consumption->total_fat;
    }

    #[ArrayShape(['calories' => "array[]", 'protein' => "\array[][]", 'carbs' => "\array[][]", 'fat' => "\array[][]"])]
    protected function getConsumptionsAsArray(): array
    {
        return [
            'calories' => [
                'total' => [
                    'amount' => $this->total_calories,
                    'unit' => 'kcal'
                ],
                'consumed' => [
                    'amount' => $this->consumed_calories,
                    'unit' => 'kcal'
                ],
            ],
            'protein' => [
                'total' => [
                    'weight' => [
                        'amount' => $this->total_protein,
                        'unit' => 'g'
                    ],
                    'energy' => [
                        'amount' => $this->total_protein * 4,
                        'unit' => 'kcal'
                    ],
                ],
                'consumed' => [
                    'weight' => [
                        'amount' => $this->consumed_protein,
                        'unit' => 'g'
                    ],
                    'energy' => [
                        'amount' => $this->consumed_protein * 4,
                        'unit' => 'kcal'
                    ],
                ]
            ],
            'carbs' => [
                'total' => [
                    'weight' => [
                        'amount' => $this->total_carbs,
                        'unit' => 'g'
                    ],
                    'energy' => [
                        'amount' => $this->total_carbs * 4,
                        'unit' => 'kcal'
                    ],
                ],
                'consumed' => [
                    'weight' => [
                        'amount' => $this->consumed_carbs,
                        'unit' => 'g'
                    ],
                    'energy' => [
                        'amount' => $this->consumed_carbs * 4,
                        'unit' => 'kcal'
                    ],
                ]
            ],
            'fat' => [
                'total' => [
                    'weight' => [
                        'amount' => $this->total_fat,
                        'unit' => 'g'
                    ],
                    'energy' => [
                        'amount' => $this->total_fat * 9,
                        'unit' => 'kcal'
                    ],
                ],
                'consumed' => [
                    'weight' => [
                        'amount' => $this->consumed_fat,
                        'unit' => 'g'
                    ],
                    'energy' => [
                        'amount' => $this->consumed_fat * 9,
                        'unit' => 'kcal'
                    ],
                ]
            ]
        ];
    }
}
