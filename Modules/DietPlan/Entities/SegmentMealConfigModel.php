<?php

namespace Modules\DietPlan\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

/**
 * @method static forceCreate(array $array)
 * @property mixed $order
 * @property mixed $name
 * @property mixed $time
 */
class SegmentMealConfigModel extends Model
{
    use HasFactory;
    protected $connection = 'diet_plans';
    protected $table = 'meals_config';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\DietPlanCache\Database\factories\DietPlanCalendarSegmentMealConfigModelFactory::new();
    }

    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            'order' => $this->order,
            'name' => $this->name,
            'time' => $this->time
        ]);
    }
}
