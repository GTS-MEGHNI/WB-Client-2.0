<?php

namespace Modules\DietPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static forceCreate(array $array)
 * @method static where(string[] $array)
 * @property mixed $start_date
 * @property mixed $end_date
 * @property mixed $value
 */
class TDEEDistModel extends Model
{
    use HasFactory;

    protected $connection = 'classroom';
    protected $table = 'tdee_dist';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Classroom\Database\factories\TDEEDistModelFactory::new();
    }

    #[ArrayShape(['startDate' => "mixed", 'endDate' => "mixed", 'value' => "mixed"])]
    public function toArray(): array
    {
        return [
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'value' => $this->value
        ];
    }
}
