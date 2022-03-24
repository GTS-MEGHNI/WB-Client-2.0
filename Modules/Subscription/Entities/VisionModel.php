<?php

namespace Modules\Subscription\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

/**
 * @method static forceCreate(array $array)
 * @property mixed $weight_goal
 * @property mixed $request
 * @property mixed $has_request
 * @property mixed $objectives
 * @property mixed $will_practise_sport
 * @property mixed $budget
 * @property mixed $sport_times
 */
class VisionModel extends Model
{
    use HasFactory;

    protected $table = 'visions';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Checkout\Database\factories\VisionModelFactory::new();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_SUBSCRIPTIONS'));
    }

    #[Pure] public function toArray(): array
    {
        return Utility::array_filter([
            "weightGoal" => $this->weight_goal,
            "objectives" => $this->objectives,
            "budget" => $this->budget,
            "willPractiseSport" => $this->will_practise_sport,
            "sportPracticeTimes" => $this->sport_times,
            "hasSpecialRequest" => $this->has_request,
            "specialRequest" => $this->request
        ]);
    }

    public function getWillPractiseSportAttribute($value): bool
    {
        return boolval($value);
    }

    public function getHasRequestAttribute($value): bool
    {
        return boolval($value);
    }
}
