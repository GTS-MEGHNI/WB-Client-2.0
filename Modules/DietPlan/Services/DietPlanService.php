<?php

namespace Modules\DietPlan\Services;

use Illuminate\Support\Facades\Cache;
use Modules\DietPlan\Entities\CalendarModel;
use Modules\DietPlan\Entities\CalendarSegmentModel;
use Modules\DietPlan\Entities\DayMealModel;
use Modules\DietPlan\Entities\DietConfigModel;
use Modules\DietPlan\Entities\MealFoodModel;
use Modules\DietPlan\Entities\SegmentDayModel;
use Modules\DietPlan\Events\FoodConsumed;
use Modules\DietPlan\Events\FoodNotConsumed;
use Modules\DietPlan\Services\FoodConsumption\DayConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\MealConsumptionService;
use Modules\DietPlan\Services\FoodConsumption\SegmentConsumptionService;
use Modules\Food\Traits\Food;
use Modules\Subscription\Traits\Order;
use Throwable;

class DietPlanService
{

    use Food, Order;

    private MealFoodModel $food;

    /**
     * @throws Throwable
     */
    public function getPlan(): array
    {
        $subscription_id = $this->getUserLatestOrder()->id;
        /*if(!Cache::has($subscription_id))
            $this->cacheCalendar($subscription_id);
        return Cache::get($subscription_id);*/
        return CalendarModel::where(['order_id' => $subscription_id])
            ->first()->toArray();
    }

    public function cacheCalendar(string $subscription_id) {
        $calendar = CalendarModel::where(['order_id' => $subscription_id])
            ->first();
        Cache::put($subscription_id, $calendar->toArray());
    }

    public function markAsConsumed(): array
    {
        $this->food = MealFoodModel::find(request()->route('foodId'));
        $has_already_consumed = $this->food->consumed == 1;
        $this->food->consumed = 1;
        $this->food->save();
        if(!$has_already_consumed)
            event(new FoodConsumed($this->food));
        return $this->response();
    }

    public function markAsNotConsumed(): array
    {
        $this->food = MealFoodModel::find(request()->route('foodId'));
        $has_revert_consume = $this->food->consumed == 1;
        $this->food->consumed = 0;
        $this->food->save();
        if($has_revert_consume)
            event(new FoodNotConsumed($this->food));
        return $this->response();
    }

    public function getFood(): array
    {
        return MealFoodModel::find(request()->route('foodId'))?->details();
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function getConfig(): array
    {
        $order_id = $this->getUserLatestOrder()->id;
        return DietConfigModel::where(['order_id' => $order_id])->first()->toArray();
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    private function response(): array
    {
        $meal = $this->food->meal;
        $day = $meal->day;
        $segment = $day->segment;
        return [
            'id' => $this->food->id,
            'order' => $this->food->order,
            'type' => $this->getServedFoodType($this->food->native_food_id),
            'nativeId' => $this->food->native_food_id,
            'hasBeenConsumed' => $this->food->consumed,
            'consumptionState' => [
                'segment' => [
                    'id' => $segment->id,
                    'segmentConsumptionState' => (new SegmentConsumptionService(
                        CalendarSegmentModel::find($segment->id)
                    ))->getConsumption()
                ],
                'day' => [
                    'id' => $day->id,
                    'dayConsumptionState' => (new DayConsumptionService(
                        SegmentDayModel::find($day->id)
                    ))->getConsumption()
                ],
                'meal' => [
                    'id' => $meal,
                    'mealConsumptionState' => (new MealConsumptionService(
                        DayMealModel::find($meal->id)
                    ))->getConsumption()
                ]
            ]
        ];
    }

}
