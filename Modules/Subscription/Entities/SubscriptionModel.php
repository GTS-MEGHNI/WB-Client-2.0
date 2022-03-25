<?php

namespace Modules\Subscription\Entities;

use App\Utility;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Dictionary;
use Modules\Dashboard\Entities\DiaryModel;
use Modules\Dashboard\Entities\BodyProgressModel;
use Modules\Dashboard\Entities\DietProgressModel;

/**
 * @method static forceCreate(array $array)
 * @method static where(array $array)
 * @property mixed $status
 * @property mixed $created_at
 * @property mixed $expired_at
 * @property mixed $personal
 * @property mixed $order_id
 * @property mixed $body
 * @property mixed $health
 * @property mixed $fitness
 * @property mixed $vision
 * @property mixed $billing
 * @property mixed $diet
 * @property mixed $plan
 * @property mixed $type
 */
class SubscriptionModel extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
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
        //return \Modules\Checkout\Database\factories\OrderModelFactory::new();
    }

    public function personal(): HasOne
    {
        return $this->hasOne(PersonalModel::class, 'order_id');
    }

    public function body(): HasOne
    {
        return $this->hasOne(BodyModel::class, 'order_id');
    }

    public function health(): HasOne
    {
        return $this->hasOne(HealthModel::class, 'order_id');
    }

    public function fitness(): HasOne
    {
        return $this->hasOne(FitnessModel::class, 'order_id');
    }

    public function billing(): HasOne
    {
        return $this->hasOne(BillingModel::class, 'order_id');
    }

    public function vision(): HasOne
    {
        return $this->hasOne(VisionModel::class, 'order_id');
    }

    public function diaries(): HasMany
    {
        return $this->hasMany(DiaryModel::class, 'order_id');
    }

    /** @noinspection PhpUnused */
    public function bodyProgresses(): HasMany
    {
        return $this->hasMany(BodyProgressModel::class, 'order_id');
    }

    /** @noinspection PhpUnused */
    public function dietProgress(): HasMany {
        return $this->hasMany(DietProgressModel::class, 'order_id');
    }

    public function toArray(): array
    {
        return Utility::array_filter([
            'meta' => Utility::array_filter([
                'id' => $this->id,
                'createdAt' => $this->created_at->timestamp,
                'expiresOn' => $this->status == Dictionary::ACTIVE_ORDER ? $this->expired_at->timestamp : null,
            ]),
            'diet' => Utility::array_filter([
                'program' => $this->diet,
                'plan' => $this->plan,
                'type' => $this->type
            ]),
            'status' => $this->status,
            'rejectionReason' => null,
            'billing' => $this->billing->toArray(),
            'personal' => $this->personal->toArray(),
            'body' => $this->body->toArray(),
            'health' => $this->health->toArray(),
            'fitness' => $this->fitness->toArray(),
            'vision' => $this->vision->toArray()
        ]);
    }

    /** @noinspection PhpUnused */
    public function getExpiredAtAttribute($value): Carbon
    {
        return Carbon::parse($value);
    }
}
