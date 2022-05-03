<?php

namespace Modules\DietPlan\Traits;

use Modules\DietPlan\Entities\SegmentDayModel;
use Modules\DietPlan\Entities\CalendarSegmentModel;
use Modules\DietPlan\Services\FoodConsumption\DayConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\MealConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\SegmentConsumptionService;

trait FoodConsumptionStats
{

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
