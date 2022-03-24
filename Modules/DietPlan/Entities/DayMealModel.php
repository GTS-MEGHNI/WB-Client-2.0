<?php

namespace Modules\DietPlan\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\DietPlan\Traits\FoodConsumptionStats;

/**
 * @method static forceCreate(array $array)
 * @method static where(... $aram)
 * @method static find(int $meal_id)
 * @property mixed $id
 * @property mixed $order
 * @property mixed $foods
 */
class DayMealModel extends Model
{
    use HasFactory, FoodConsumptionStats;

    protected $connection = 'diet_plans';
    protected $table = 'days_meals';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['meal_id'];

    protected static function newFactory()
    {
        //return \Modules\DietPlanCache\Database\factories\CalendarSegmentDayMealFactory::new();
    }

    public function foods(): HasMany
    {
        return $this->hasMany(MealFoodModel::class, 'meal_id', 'id');
    }

    public function day() : BelongsTo {
        return $this->belongsTo(SegmentDayModel::class, 'day_id', 'id');
    }

    public function toArray(): array
    {
        return Utility::remove_array_shape_tag([
            'order' => $this->order,
            'id' => $this->id,
            'meal' => $this->foods->toArray(),
            'consumptionState' => $this->getStats(),
        ]);
    }
}
