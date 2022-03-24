<?php

namespace Modules\DietPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\ArrayShape;
use Modules\DietPlan\Traits\FoodConsumptionStats;

/**
 * @method static forceCreate(array $array)
 * @method static find(int $segment_id)
 * @method static where(...$array)
 * @property mixed $id
 * @property mixed $order
 * @property mixed $start_date
 * @property mixed $mealsConfig
 * @property mixed $days
 */
class CalendarSegmentModel extends Model
{
    use HasFactory, FoodConsumptionStats;

    protected $connection = 'diet_plans';
    protected $table = 'segments';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\DietPlanCache\Database\factories\DietPlanCalendarSegmentFactory::new();
    }

    public function mealsConfig(): HasMany
    {
        return $this->hasMany(SegmentMealConfigModel::class, 'segment_id', 'id');
    }

    public function days(): HasMany
    {
        return $this->hasMany(SegmentDayModel::class, 'segment_id', 'id');
    }

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(CalendarModel::class, 'calendar_id', 'id');
    }

    #[ArrayShape(['order' => "mixed", 'id' => "mixed", 'consumptionState' => "array", 'startDate' => "mixed", 'mealsConfig' => "", 'days' => ""])] public function toArray(): array
    {
        return [
            'order' => $this->order,
            'id' => $this->id,
            'consumptionState' => $this->getStats(),
            'startDate' => $this->start_date,
            'mealsConfig' => $this->mealsConfig->toArray(),
            'days' => $this->days->toArray()
        ];
    }
}
