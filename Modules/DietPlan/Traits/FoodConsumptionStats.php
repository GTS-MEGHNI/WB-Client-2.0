<?php

namespace Modules\DietPlan\Traits;

use JetBrains\PhpStorm\ArrayShape;
use Modules\DietPlan\Entities\SegmentDayModel;
use Modules\DietPlan\Entities\CalendarSegmentModel;
use Modules\DietPlan\Services\FoodConsumption\DayConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\MealConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\SegmentConsumptionService;

trait FoodConsumptionStats
{

    #[ArrayShape(['calories' => "array[]", 'protein' => "\array[][]", 'carbs' => "\array[][]", 'fat' => "\array[][]"])]
    private function getStats(): array
    {
        if ($this instanceof CalendarSegmentModel) {
            return (new SegmentConsumptionService($this))->getConsumption();
        } elseif ($this instanceof SegmentDayModel) {
            return (new DayConsumptionService($this))->getConsumption();
        } else {
            return (new MealConsumptionService($this))->getConsumption();
        }
    }

}
