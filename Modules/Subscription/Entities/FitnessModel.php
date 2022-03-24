<?php

namespace Modules\Subscription\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

/**
 * @method static forceCreate(array $array)
 * @property mixed $is_sport
 * @property mixed $sport_type
 * @property mixed $fitness_sport_times
 * @property mixed $is_using_supplement
 * @property mixed $had_diet
 * @property mixed $diet_exp
 * @property mixed $has_food_cons
 * @property mixed $food_cons
 */
class FitnessModel extends Model
{
    use HasFactory;

    protected $table = 'fitness';
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
        //return \Modules\Checkout\Database\factories\FitnessModelFactory::new();
    }



    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            "isPractisingSport" => $this->is_sport,
            "sportType" => $this->sport_type,
            "sportPracticeTimes" => $this->fitness_sport_times,
            "isUsingSupplements" => $this->is_using_supplement,
            "hasDietExperience" => $this->had_diet,
            "dietExperience" => $this->diet_exp,
            "hasFoodConsiderations" => $this->has_food_cons,
            "foodConsiderations" => $this->food_cons
        ]);
    }

    public function getIsSportAttribute($value): bool
    {
        return boolval($value);
    }

    public function getIsUsingSupplementAttribute($value): bool
    {
        return boolval($value);
    }

    public function getHadDietAttribute($value): bool
    {
        return boolval($value);
    }

    public function getHasFoodConsAttribute($value): bool
    {
        return boolval($value);
    }


}
