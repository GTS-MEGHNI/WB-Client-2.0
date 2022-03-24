<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $id
 * @property mixed $created_at
 * @property mixed $protein_recommended
 * @property mixed $protein_consumed
 * @property mixed $calories_consumed
 * @property mixed $calories_recommended
 * @property mixed $carbs_consumed
 * @property mixed $carbs_recommended
 * @property mixed $fat_consumed
 * @property mixed $fat_recommended
 * @property mixed $water_consumed
 * @property mixed $water_recommended
 * @property float|int|mixed|string $date
 * @property mixed $order_id
 * @method static where(... $params)
 */
class DietProgressModel extends Model
{
    use HasFactory;

    protected $connection = 'metrics';
    protected $table = 'diet_progress';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        // return \Modules\Dashboard\Database\factories\DietProgressModelFactory::new();
    }

    #[ArrayShape(['id' => "mixed", 'date' => "", 'calories' => "array", 'protein' => "array", 'carbs' => "array", 'fat' => "array", 'water' => "array"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'calories' => [
                'consumed' => $this->calories_consumed,
                'recommended' => $this->calories_recommended
            ],
            'protein' => [
                'consumed' => $this->protein_consumed,
                'recommended' => $this->protein_recommended
            ],
            'carbs' => [
                'consumed' => $this->carbs_consumed,
                'recommended' => $this->carbs_recommended
            ],
            'fat' => [
                'consumed' => $this->fat_consumed,
                'recommended' => $this->fat_recommended
            ],
            'water' => [
                'consumed' => $this->water_consumed,
                'recommended' => $this->water_recommended
            ]
        ];
    }
}
