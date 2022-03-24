<?php

namespace Modules\DietPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @property mixed $id
 * @property mixed $days
 * @property mixed $segments
 */
class CalendarModel extends Model
{
    use HasFactory;

    protected $connection = 'diet_plans';
    protected $table = 'calendars';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\DietPlanCache\Database\factories\DietPlanModelFactory::new();
    }

    public function segments(): hasMany
    {
        return $this->hasMany(CalendarSegmentModel::class, 'calendar_id', 'id');
    }

    public function toArray(): array
    {
        return $this->segments->toArray();
    }

    public static function booted()
    {
        static::deleting(function ($model) {
            $model->segments()->get()->each(function ($segment) {
                $segment->days()->get()->each(function ($day) {
                    $day->meals()->get()->each(function ($meal) {
                        $meal->foods()->get()->each(function($food) {
                            $food->delete();
                        });
                        $meal->delete();
                    });
                    $day->delete();
                });
                $segment->mealsConfig()->get()->each->delete();
                $segment->delete();
            });
        });
    }

}
