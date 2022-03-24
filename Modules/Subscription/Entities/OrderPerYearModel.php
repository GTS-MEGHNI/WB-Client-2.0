<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static where(array $array)
 * @method static create(array $array)
 */
class OrderPerYearModel extends Model
{
    use HasFactory;

    protected $table = 'orders_per_years';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = ['year', 'count'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_SUBSCRIPTIONS'));
    }

    protected static function newFactory()
    {
        //return \Modules\Checkout\Database\factories\OrderPerYearModelFactory::new();
    }
}
