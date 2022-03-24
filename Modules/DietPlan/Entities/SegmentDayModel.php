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
 * @method static find(int $segment_id)
 * @method static where(... $params)
 * @property mixed $id
 * @property mixed $order
 * @property mixed $mealList
 * @property mixed $meals
 */
class SegmentDayModel extends Model
{
    use HasFactory, FoodConsumptionStats;
    protected $connection = 'diet_plans';
    protected $table = 'days';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['segment_id'];

    protected static function newFactory()
    {
        //return \Modules\DietPlanCache\Database\factories\DietPlanCalendarSegmentAYModelFactory::new();
    }

    public function meals(): HasMany {
        return $this->hasMany(DayMealModel::class,
            'day_id', 'id');
    }

    public function segment() : BelongsTo {
        return $this->belongsTo(CalendarSegmentModel::class, 'segment_id', 'id');
    }

    public function toArray(): array
    {
        return Utility::remove_array_shape_tag([
            'order' => $this->order,
            'id' => $this->id,
            'mealList' => $this->meals->toArray(),
            'consumptionState' => $this->getStats(),
        ]);
    }

}
