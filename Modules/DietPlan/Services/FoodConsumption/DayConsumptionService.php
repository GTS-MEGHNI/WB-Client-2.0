<?php

namespace Modules\DietPlan\Services\FoodConsumption;

use JetBrains\PhpStorm\ArrayShape;
use Modules\DietPlan\Entities\SegmentDayModel;
use Modules\DietPlan\Interfaces\FoodConsumption;

class DayConsumptionService extends ConsumptionService implements FoodConsumption
{
    private SegmentDayModel $day;

    public function __construct(SegmentDayModel $day)
    {
        $this->day = $day;
    }

    #[ArrayShape(['calories' => "array[]", 'protein' => "\array[][]", 'carbs' => "\array[][]", 'fat' => "\array[][]"])]
    public function getConsumption() : array {
        $this->calculate();
        return $this->getConsumptionsAsArray();
    }

    function calculate(): void
    {
        $collations = $this->day->meals;
        foreach ($collations as $collation) {
            $collation_consumption = new MealConsumptionService($collation);
            $collation_consumption->calculate();
            $this->sum($collation_consumption);
        }
    }
}
