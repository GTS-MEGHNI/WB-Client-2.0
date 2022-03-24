<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $method
 * @property mixed $time_unit
 * @property mixed $time
 * @method static forceCreate(array $array)
 */
class FoodCookingModel extends Model
{
    use HasFactory;

    protected $connection = 'food';
    protected $table = 'food_cooking';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\RecipeCookingModelFactory::new();
    }

    #[ArrayShape(['method' => "mixed", 'time' => "array"])]
    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'time' => [
                'amount' => $this->time,
                'unit' => $this->time_unit
            ]
        ];
    }
}
