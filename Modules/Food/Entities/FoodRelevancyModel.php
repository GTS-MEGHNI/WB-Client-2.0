<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static forceCreate(array $array)
 * @method static find(string $food_id)
 */
class FoodRelevancyModel extends Model
{
    use HasFactory;
    protected $connection = 'food';
    protected $table = 'food_relevancy';
    protected $primaryKey = 'food_id';
    public $incrementing = false;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\FoodRelevancyModelFactory::new();
    }
}
