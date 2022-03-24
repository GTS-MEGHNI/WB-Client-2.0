<?php

namespace Modules\Food\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $facts
 * @property mixed $quantity
 * @property mixed $measure_type
 * @property mixed $weight
 * @property mixed $weight_unit
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 */
class MeasurementModel extends Model
{
    use HasFactory;

    protected $fillable = ['food_id'];
    protected $connection = 'food';
    protected $table = 'measurements';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected static function newFactory()
    {
       // return \Modules\Food\Database\factories\MeasurmentModelFactory::new();
    }

    public function facts(): HasOne {
        return $this->hasOne(FactModel::class, 'measurement_id');
    }

    #[ArrayShape(['quantity' => "array", 'weight' => "array", 'facts' => ""])]
    public function toArray(): array
    {
        return [
            'quantity' => [
                'amount' => $this->quantity,
                'unit' => $this->measure_type,
            ],
            'weight' => [
                'amount' => $this->weight,
                'unit' => $this->weight_unit
            ],
            'facts' => $this->facts?->toArray()
        ];
    }
}
