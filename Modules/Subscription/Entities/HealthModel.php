<?php

namespace Modules\Subscription\Entities;

use App\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\Pure;

/**
 * @method static forceCreate(array $array)
 * @property mixed $has_phy_issues
 * @property mixed $phy_issues
 * @property mixed $taking_medication
 * @property mixed $medication
 * @property mixed $has_mental_issues
 * @property mixed $mental_state
 * @property mixed $is_pregnant
 * @property mixed $pregnancy_month
 * @property mixed $breast_feeding
 * @property mixed $lactation_month
 */
class HealthModel extends Model
{
    use HasFactory;

    protected $table = 'health';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [];

    protected static function newFactory()
    {
        //return \Modules\Checkout\Database\factories\HealthModelFactory::new();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(env('DB_CONNECTION_SUBSCRIPTIONS'));
    }

    #[Pure] public function toArray()
    {
        return Utility::array_filter([
            "hasPhysicalHealthIssues" => $this->has_phy_issues,
            "physicalHealthIssues" => $this->phy_issues,
            "isTakingMedications" => $this->taking_medication,
            "medications" => $this->medication,
            "hasMentalHealthIssues" => $this->has_mental_issues,
            "mentalHealthState" => $this->mental_state,
            "isPregnant" => $this->is_pregnant,
            "pregnancyMonth" => $this->pregnancy_month,
            "isBreastfeeding" => $this->breast_feeding,
            "lactationMonth" => $this->lactation_month
        ]);
    }

    public function getHasPhyIssuesAttribute($value): bool
    {
        return boolval($value);
    }

    public function getTakingMedicationAttribute($value): bool
    {
        return boolval($value);
    }

    public function getHasMentalIssuesAttribute($value): bool
    {
        return boolval($value);
    }

    public function getIsPregnantAttribute($value): ?bool
    {
        if ($value == null)
            return null;
        else return boolval($value);
    }

    public function getBreastFeedingAttribute($value): ?bool
    {
        if ($value == null)
            return null;
        else return boolval($value);
    }


}
