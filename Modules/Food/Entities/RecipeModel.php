<?php

namespace Modules\Food\Entities;

use App\Dictionary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static join(... $params)
 * @method static select(string[] $array)
 * @method static forceCreate(array $array)
 * @method static where(... $param)
 * @method static find(string $recipe_id)
 * @property mixed $name
 * @property mixed $thumbnail
 * @property mixed $full
 * @property mixed $measurement
 * @property mixed $ingredients
 * @property mixed $description
 * @property mixed $preparationSteps
 * @property mixed $cooking
 * @property mixed $notes
 * @property mixed $steps
 * @property mixed $photos
 * @property mixed $id
 * @property mixed $directions
 * @property mixed $measurements
 */
class RecipeModel extends Model
{
    use HasFactory;

    protected $connection = 'food';
    protected $table = 'recipes';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [];

    public function measurements(): HasMany
    {
        return $this->hasMany(MeasurementModel::class, 'food_id', 'id');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredientModel::class, 'recipe_id', 'id');
    }

    public function cooking(): HasOne
    {
        return $this->hasOne(FoodCookingModel::class, 'food_id', 'id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(FoodNoteModel::class, 'food_id', 'id');
    }

    public function directions(): HasMany
    {
        return $this->hasMany(FoodPreparationStepModel::class,
            'food_id', 'id')->orderBy('id');
    }

    public function photos(): hasOne
    {
        return $this->hasOne(FoodPictureModel::class, 'food_id', 'id');
    }

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\RecipeFactory::new();
    }

    #[ArrayShape(['id' => "mixed", 'name' => "mixed", 'type' => "string", 'photo' => "", 'measurement' => "array"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => Dictionary::NATIVE_RECIPE,
            'photo' => $this->photos?->toArray(),
            'measurement' => $this->measurements->first()->toArray()
        ];
    }

    public function details(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => Dictionary::NATIVE_RECIPE,
            'description' => $this->description,
            'directions' => $this->directions->toArray(),
            'photo' => $this->photos?->toArray(),
            'measurement' => $this->measurements->toArray(),
            'ingredients' => $this->ingredients->toArray(),
            'cooking' => $this->cooking->toArray(),
            'notes' => $this->notes->toArray(),
            'preparationTime' => [
                'amount' => null,
                'unit' => null
            ],
        ];
    }
}
