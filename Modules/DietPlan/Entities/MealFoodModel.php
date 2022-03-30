<?php

namespace Modules\DietPlan\Entities;

use App\Dictionary;
use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Food\Entities\FoodCookingModel;
use Modules\Food\Entities\FoodNoteModel;
use Modules\Food\Entities\FoodPreparationStepModel;
use Modules\Food\Entities\IngredientModel;
use Modules\Food\Entities\MeasurementModel;
use Modules\Food\Entities\RecipeModel;
use Modules\Food\Services\MeasurementCalculation\MeasurementCalculatorService;
use Modules\Food\Traits\Food;

/**
 * @property mixed $order
 * @property mixed $id
 * @property mixed $consumed
 * @property mixed $cooking
 * @property mixed $directions
 * @property mixed $ingredient
 * @property mixed $notes
 * @property mixed $measurement
 * @property mixed $recipe
 * @property mixed $native_food_id
 * @property mixed $meal
 * @method static where(array $array)
 * @method static find(mixed $id)
 * @method static forceCreate(array $array)
 */
class MealFoodModel extends Model
{
    use HasFactory, Food;

    protected $connection = 'diet_plans';
    protected $table = 'meals_foods';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected static function newFactory()
    {
        //return \Modules\DietPlanCache\Database\factories\CalendarSegmentDayCollationMealModelFactory::new();
    }

    public function cooking(): HasOne
    {
        return $this->hasOne(FoodCookingModel::class, 'food_id', 'id');
    }

    public function directions(): HasOne
    {
        return $this->hasOne(FoodPreparationStepModel::class, 'food_id', 'id');
    }

    public function notes(): HasOne
    {
        return $this->hasOne(FoodNoteModel::class, 'food_id', 'id');
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(IngredientModel::class, 'native_food_id', 'id');
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(RecipeModel::class, 'native_food_id', 'id');
    }

    public function meal() : BelongsTo {
        return $this->belongsTo(DayMealModel::class, 'meal_id', 'id');
    }

    public function measurement(): HasOne
    {
        return $this->hasOne(MeasurementModel::class, 'food_id', 'id');
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function toArray(): array
    {
        return [
            'order' => $this->order,
            'type' => $this->getServedFoodType($this->native_food_id),
            'id' => $this->id,
            'nativeId' => $this->native_food_id,
            'hasBeenConsumed' => $this->consumed
        ];
    }

    public function details(): array
    {
        return match ($this->getFoodTypeByID($this->native_food_id)) {
            Dictionary::INGREDIENT => $this->detailsAsServedIngredient(),
            Dictionary::RECIPE => $this->detailsAsRecipe(),
        };
    }


    public function detailsAsServedIngredient(): array
    {
        return Utility::remove_array_shape_tag([
            'id' => $this->id,
            'nativeId' => $this->native_food_id,
            'name' => $this->ingredient->name,
            'category' => $this->ingredient->category,
            'photo' => $this->ingredient->photos->toArray(),
            'description' => $this->ingredient->description,
            'type' => Dictionary::SERVED_INGREDIENT,
            'notes' => $this->notes?->toArray(),
            'measurement' => (new MeasurementCalculatorService($this))->getMeasurement()
        ]);
    }

    public function detailsAsRecipe(): array
    {
        return [
            'id' => $this->id,
            'nativeId' => $this->native_food_id,
            'name' => $this->recipe->name,
            'type' => Dictionary::SERVED_RECIPE,
            'description' => $this->recipe->description,
            'directions' => $this->recipe->directions->toArray(),
            'photo' => $this->recipe->photos->toArray(),
            'measurement' => (new MeasurementCalculatorService($this))->getMeasurement(),
            'ingredients' => $this->recipe->ingredients->toArray(),
            'cooking' => $this->recipe->cooking?->toArray(),
            'notes' => $this->recipe->notes?->toArray(),
            'preparationTime' => [
                'amount' => null,
                'unit' => null
            ],
        ];
    }

    public function getConsumedAttribute($value): ?bool
    {
        return $value == null ? null : boolval($value);
    }

}
