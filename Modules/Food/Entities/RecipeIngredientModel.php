<?php

namespace Modules\Food\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Food\Services\MeasurementCalculation\MeasurementCalculatorService;
use Modules\Food\Traits\Food;

/**
 * @property mixed $ingredient
 * @property mixed $measurement
 * @property mixed $ingredient_id
 * @property mixed $quantity_unit
 * @property mixed $quantity
 * @property mixed $weight_unit
 * @property mixed $facts
 * @property mixed $weight_amount
 * @method static forceCreate(array $array)
 */
class RecipeIngredientModel extends Model
{
    use HasFactory, Food;

    protected $connection = 'food';
    protected $table = 'recipes_ingredients';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        // return \Modules\Food\Database\factories\RecipeIngredientModelFactory::new();
    }

    public function ingredient(): belongsTo
    {
        return $this->belongsTo(IngredientModel::class, 'ingredient_id', 'id');
    }

    public function measurement(): HasOne
    {
        return $this->hasOne(MeasurementModel::class, 'food_id', 'ingredient_id');
    }

    #[ArrayShape(['id' => "mixed", 'name' => "", 'category' => "", 'photo' => "", 'measurement' => "array"])]
    public function toArray(): array
    {
        return Utility::array_filter([
            'id' => $this->ingredient_id,
            'name' => $this->ingredient->name,
            'category' => $this->ingredient->category,
            'photo' => $this->ingredient->photos->toArray(),
            'measurement' => (new MeasurementCalculatorService($this))->getMeasurement()
        ]);
    }


}
