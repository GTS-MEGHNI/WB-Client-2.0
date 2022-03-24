<?php

namespace Modules\DietPlan\Events;

use Illuminate\Queue\SerializesModels;
use Modules\DietPlan\Entities\MealFoodModel;

class FoodNotConsumed
{
    use SerializesModels;

    public MealFoodModel $foodModel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MealFoodModel $foodModel)
    {
        $this->foodModel = $foodModel;
    }
}
