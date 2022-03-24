<?php

namespace Modules\Plans\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $currency
 * @property mixed $price
 */
class PriceModel extends Model
{
    use HasFactory;

    protected $connection = 'plans';
    protected $table = 'prices';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Plans\Database\factories\PriceModelFactory::new();
    }

    #[ArrayShape(['currency' => "mixed", 'value' => "mixed"])]
    public function toArray(): array
    {
        return [
            'currency' => $this->currency,
            'value' => $this->price
        ];
    }
}
