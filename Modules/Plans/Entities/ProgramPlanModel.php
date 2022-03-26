<?php

namespace Modules\Plans\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use JetBrains\PhpStorm\Pure;

/**
 * @property mixed $duration
 * @property mixed $description
 * @property mixed $price
 * @property mixed $enabled
 */
class ProgramPlanModel extends Model
{
    use HasFactory;

    protected $connection = 'plans';
    protected $table = 'plans';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        // return \Modules\Plans\Database\factories\ProgramPlanModelFactory::new();
    }

    public function price(): HasMany
    {
        return $this->HasMany(PriceModel::class, 'plan_id', 'id');
    }

    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            'plan' => $this->duration,
            'description' => $this->description,
            'enabled' => boolval($this->enabled),
            'price' => Utility::currencyFilter($this->price->toArray())
        ]);
    }
}
