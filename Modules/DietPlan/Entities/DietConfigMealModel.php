<?php

namespace Modules\DietPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @property mixed $name
 * @property mixed $time
 */
class DietConfigMealModel extends Model
{
    use HasFactory;

    protected $connection = 'classroom';
    protected $table = 'settings_meals';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Classroom\Database\factories\DietConfigMealModelFactory::new();
    }

    #[ArrayShape(['name' => "mixed", 'time' => "mixed"])] public function toArray(): array
    {
        return [
            'name'=> $this->name,
            'time'=> $this->time
        ];
    }
}
