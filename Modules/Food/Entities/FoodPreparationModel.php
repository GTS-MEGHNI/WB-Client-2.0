<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static forceCreate(array $array)
 * @property mixed $time_unit
 * @property mixed $time
 */
class FoodPreparationModel extends Model
{
    use HasFactory;

    protected $connection = 'food';
    protected $table = 'food_preparation';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\FoodPreparationModelFactory::new();
    }

    #[ArrayShape(['amount' => "mixed", 'unit' => "mixed"])]
    public function toArray(): array
    {
        return [
            'amount' => $this->time,
            'unit' => $this->time_unit
        ];
    }
}
