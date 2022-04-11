<?php

namespace Modules\DietPlan\Listeners;

use Carbon\Carbon;
use Modules\Dashboard\Entities\DietProgressModel;
use Modules\DietPlan\Events\FoodConsumed;
use Modules\DietPlan\Events\FoodNotConsumed;
use Modules\DietPlan\Services\FoodConsumption\DayConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\FoodConsumptionService;

class UpdateDietProgress
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param FoodConsumed|FoodNotConsumed $event
     * @return void
     */
    public function handle(FoodConsumed|FoodNotConsumed $event)
    {
        if ($event instanceof FoodConsumed)
            $this->incrementDietProgress($event);
        else
            $this->decrementDietProgress($event);
    }

    private function incrementDietProgress(FoodConsumed|FoodNotConsumed $event)
    {
        $facts_calculator_service = new FoodConsumptionService($event->foodModel);
        $facts_calculator_service->calculate();
        $day = $event->foodModel->meal->day;
        $day_recommended_metrics = new DayConsumptionService($day);
        $day_recommended_metrics->calculate();
        $segment = $day->segment;
        $record_metric_date = Carbon::parse($segment->start_date)->addDays($day->order - 1);
        $row = DietProgressModel::where(['date' => $record_metric_date->timestamp])->first();
        if($row == null) {
            $row = new DietProgressModel;
            $row->order_id = $segment->calendar->order_id;
            $row->date = $record_metric_date->timestamp;
            $row->protein_consumed = $facts_calculator_service->consumed_protein;
            $row->carbs_consumed = $facts_calculator_service->consumed_carbs;
            $row->fat_consumed = $facts_calculator_service->consumed_fat;
            $row->calories_consumed = $facts_calculator_service->consumed_calories;
            $row->protein_recommended = $day_recommended_metrics->total_protein;
            $row->carbs_recommended = $day_recommended_metrics->total_carbs;
            $row->fat_recommended = $day_recommended_metrics->total_fat;
            $row->calories_recommended = $day_recommended_metrics->total_calories;
        } else {
            $row->increment('protein_consumed', $facts_calculator_service->consumed_protein);
            $row->increment('fat_consumed', $facts_calculator_service->consumed_fat);
            $row->increment('calories_consumed', $facts_calculator_service->consumed_calories);
            $row->increment('carbs_consumed', $facts_calculator_service->consumed_carbs);
        }
        $row->save();
    }

    private function decrementDietProgress(FoodConsumed|FoodNotConsumed $event)
    {
        $facts_calculator_service = new FoodConsumptionService($event->foodModel);
        $facts_calculator_service->calculate();
        $day = $event->foodModel->meal->day;
        $day_recommended_metrics = new DayConsumptionService($day);
        $day_recommended_metrics->calculate();
        $segment = $day->segment;
        $record_metric_date = Carbon::parse($segment->start_date)->addDays($day->order - 1);
        $row = DietProgressModel::where(['date' => $record_metric_date->timestamp])->first();
        dd($facts_calculator_service);
        $row->decrement('protein_consumed', $facts_calculator_service->consumed_protein);
        $row->decrement('fat_consumed', $facts_calculator_service->consumed_fat);
        $row->decrement('calories_consumed', $facts_calculator_service->consumed_calories);
        $row->decrement('carbs_consumed', $facts_calculator_service->consumed_carbs);
        $row->save();
    }
}
