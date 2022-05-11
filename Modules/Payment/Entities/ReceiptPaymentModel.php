<?php

namespace Modules\Payment\Entities;

use App\Dictionary;
use App\Utility;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

/**
 * @method static forceCreate(array $array)
 * @method static find(string $id)
 * @method static where(array $array)
 * @property mixed $payment_method
 * @property mixed $id
 * @property mixed $receipt_path
 * @property mixed $file_type
 */
class ReceiptPaymentModel extends Model
{
    use HasFactory;

    protected $table = 'receipts';
    public $incrementing = false;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_PAYMENT'));
    }

    protected static function newFactory()
    {
        //return \Modules\Payment\Database\factories\ReceiptPaymentModelFactory::new();
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->payment_method,
            'currency' => Dictionary::DINAR_CURRENCY
        ];
    }

    #[Pure] public function details(): array
    {
        return Utility::array_filter([
            'id' => $this->id,
            'method' => $this->payment_method,
            'receipt' => Utility::array_filter([
                'url' => $this->receipt_path,
                'type' => $this->file_type
            ])
        ]);
    }


    public function receiptPath(): Attribute
    {
        return new Attribute(
            get: fn($value) => env('APP_URL') . '/payments/' . $value
        );
    }
}
