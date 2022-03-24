<?php

namespace Modules\DietPlan\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Pure;
use Modules\DietPlan\Traits\TDEEConfig;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @property mixed $start_date
 * @property mixed $time_enabled
 * @property mixed $calendar_view
 * @property mixed $meals
 * @property mixed $tdee_marge_enabled
 * @property mixed $tdee_marge_top
 * @property mixed $tdee_marge_bottom
 * @property mixed $protein_enabled
 * @property mixed $protein_factor
 * @property mixed $weight_threshold
 * @property mixed $water_enabled
 * @property mixed $water_factor
 * @property mixed $order_id
 */
class DietConfigModel extends Model
{
    use HasFactory, TDEEConfig;

    protected $connection = 'classroom';
    protected $table = 'diet_settings';
    protected $primaryKey = 'id';
    public $incrementing = true;


    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Classroom\Database\factories\DietConfigModelFactory::new();
    }

    public function meals(): HasMany
    {
        return $this->hasMany(DietConfigMealModel::class, 'setting_id');
    }


    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            'startDate' => $this->start_date,
            'enableMealTiming' => $this->time_enabled,
            'calendarView' => $this->calendar_view,
            'meals' => $this->meals->toArray(),
            'tdeeToleranceMargin' => Utility::array_filter([
                'enabled' => $this->tdee_marge_enabled,
                "bottomEdge" => $this->tdee_marge_bottom,
                "topEdge" => $this->tdee_marge_top
            ]),
            'proteinDailyNeeds' => Utility::array_filter([
                "enabled" => $this->protein_enabled,
                "factor" => $this->protein_factor,
                "weightThreshold" => $this->weight_threshold
            ]),
            'waterDailyNeeds' => Utility::array_filter([
                'enabled' => $this->water_enabled,
                'factor' => $this->water_factor
            ]),
            'tdeeDistribution' => $this->start_date !== null ?
                $this->getTDEEDist($this->order_id) : null
        ]);
    }

    public function getTimeEnabledAttribute($value): bool
    {
        return boolval($value);
    }

    public function getTdeeMargeEnabledAttribute($value) : bool {
        return boolval($value);
    }

    public function getProteinEnabledAttribute($value) : bool {
        return boolval($value);
    }

    public function getWaterEnabledAttribute($value) : bool {
        return boolval($value);
    }
}
