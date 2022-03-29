<?php

namespace Modules\Subscription\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\Pure;
use Modules\Payment\Entities\ReceiptPaymentModel;
use Modules\Subscription\Traits\Order;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @method static find(int $id)
 * @property mixed $total
 * @property mixed $payment_method
 * @property mixed $proof_path
 * @property mixed $type
 * @property mixed $file_type
 * @property mixed $price
 * @property mixed $is_paid
 * @property mixed $id
 * @property mixed $payment
 */
class BillingModel extends Model
{
    use HasFactory, Order;

    protected $table = 'billings';

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_SUBSCRIPTIONS'));
    }

    protected static function newFactory()
    {
        //return \Modules\Checkout\Database\factories\BillingModelFactory::new();
    }

    public function price(): HasMany
    {
        return $this->hasMany(BillingPriceModel::class, 'billing_id', 'id');
    }

    public function payment() : HasOne {
        return $this->hasOne(ReceiptPaymentModel::class, 'billing_id');
    }

    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            'total' => $this->price->toArray(),
            'isPaid' => boolval($this->is_paid),
            'payment' => $this->payment?->toArray()
        ]);
    }
}
