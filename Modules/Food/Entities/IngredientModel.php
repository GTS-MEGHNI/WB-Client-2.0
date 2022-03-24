<?php

namespace Modules\Food\Entities;

use App\Dictionary;
use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed $name
 * @property mixed $category
 * @property mixed $description
 * @property mixed $thumbnail
 * @property mixed $full
 * @property mixed $measurements
 * @property mixed $photos
 * @property mixed $id
 * @method static forceCreate(array $array)
 * @method static find(mixed $id)
 * @method static where(... $params)
 * @method static select(string[] $array)
 */
class IngredientModel extends Model
{
    use HasFactory;

    protected $connection = 'food';
    protected $table = 'ingredients';
    protected $primaryKey = 'id';
    public $incrementing = false;


    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Food\Database\factories\IngredientModelFactory::new();
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(MeasurementModel::class, 'food_id', 'id');
    }

    public function photos(): hasOne {
        return $this->hasOne(FoodPictureModel::class, 'food_id', 'id');
    }

    public function toArray(): array
    {
        if($this->photos()->first() == null)
            dd($this);
        return Utility::array_filter([
            'id' => $this->id,
            'type' => Dictionary::NATIVE_INGREDIENT,
            'name' => $this->name,
            'photo' => $this->photos->toArray(),
            'measurement' => $this->measurements()->first()->toArray(),
            'category' => $this->category
        ]);
    }

    public function details(): array
    {
        return Utility::array_filter([
            'id' => $this->id,
            'type' => Dictionary::INGREDIENT,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'photo' => $this->photos->toArray(),
            'AvailableUnits' => $this->measurements()->pluck('measure_type'),
            'measurement' => Utility::filterMeasurement($this->measurements->toArray())
        ]);
    }
}
