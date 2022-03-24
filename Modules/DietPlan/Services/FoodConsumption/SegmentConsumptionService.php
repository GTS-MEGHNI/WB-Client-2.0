<?php

namespace Modules\DietPlan\Services\FoodConsumption;

use JetBrains\PhpStorm\ArrayShape;
use Modules\DietPlan\Entities\CalendarSegmentModel;
use Modules\DietPlan\Interfaces\FoodConsumption;

class SegmentConsumptionService extends ConsumptionService implements FoodConsumption
{
    private CalendarSegmentModel $segment;

    public function __construct(CalendarSegmentModel $segment)
    {
        $this->segment = $segment;
    }

    #[ArrayShape(['calories' => "array[]", 'protein' => "\array[][]", 'carbs' => "\array[][]", 'fat' => "\array[][]"])]
    public function getConsumption() : array {
        $this->calculate();
        return $this->getConsumptionsAsArray();
    }

    function calculate(): void
    {
        $days = $this->segment->days;
        foreach ($days as $day) {
            $day_consumption = new DayConsumptionService($day);
            $day_consumption->calculate();
            $this->sum($day_consumption);
        }
    }

}
