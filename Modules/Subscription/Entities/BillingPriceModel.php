<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $currency
 * @property mixed $price
 * @method static forceCreate(array $array)
 */
class BillingPriceModel extends Model
{
    use HasFactory;

    protected $table = 'billing_currencies';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_SUBSCRIPTIONS'));
    }

    protected static function newFactory()
    {
        //return \Modules\Subscription\Database\factories\BillingPriceModelFactory::new();
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
