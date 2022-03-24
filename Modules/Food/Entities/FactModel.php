<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $calories
 * @property mixed $protein
 * @property mixed $carbs
 * @property mixed $fat
 * @method static forceCreate(array $array)
 */
class FactModel extends Model
{
    use HasFactory;

    protected $connection = 'food';
    protected $table = 'facts';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\FactModelFactory::new();
    }

    #[ArrayShape(['calories' => "array", 'protein' => "array[]", 'carbs' => "array[]", 'fat' => "array[]"])]
    public function toArray(): array
    {
        return [
            'calories' => [
                'amount' => $this->calories,
                'unit' => 'kcal'
            ],
            'protein' => [
                'weight' => [
                    'amount' => $this->protein,
                    'unit' => 'g'
                ],
                'energy' => [
                    'amount' => $this->protein * 4,
                    'unit' => 'kcal'
                ]
            ],
            'carbs' => [
                'weight' => [
                    'amount' => $this->carbs,
                    'unit' => 'g'
                ],
                'energy' => [
                    'amount' => $this->carbs * 4,
                    'unit' => 'kcal'
                ]
            ],
            'fat' => [
                'weight' => [
                    'amount' => $this->fat,
                    'unit' => 'g'
                ],
                'energy' => [
                    'amount' => $this->fat * 9,
                    'unit' => 'kcal'
                ]
            ]
        ];
    }
}
