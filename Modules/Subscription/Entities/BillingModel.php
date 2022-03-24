<?php

namespace Modules\Subscription\Entities;

use App\Dictionary;
use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\Pure;
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
 */
class BillingModel extends Model
{
    use HasFactory, Order;

    protected $table = 'billings';
    protected $primaryKey = 'id';
    public $incrementing = false;

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

    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            'total' => $this->price->toArray(),
            'payment' => [
                'id' => $this->id,
                'method' => $this->payment_method,
                'currency' => Dictionary::DINAR_CURRENCY
            ]
            /*'payment' => Utility::array_filter([
                'isPaid' => $this->is_paid,
                'method' => $this->payment_method,
                'receipt' => Utility::array_filter([
                    'url' => $this->proof_path,
                    'type' => $this->file_type
                ])
            ])*/
        ]);
    }

    #[Pure] public function details(): array
    {
        return Utility::array_filter([
            'id' => $this->id,
            'method' => $this->payment_method,
            'receipt' => Utility::array_filter([
                'url' => $this->proof_path,
                'type' => $this->file_type
            ])
        ]);
    }

    public function getProofPathAttribute($value): ?string
    {
        return $value == null ? null : env('APP_URL') . '/payments/' . $value;
    }

    public function getIsPaidAttribute($value): bool
    {
        return boolval($value);
    }
}
