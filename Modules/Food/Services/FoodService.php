<?php

namespace Modules\Food\Services;

use Modules\Food\Traits\Food;
use Throwable;

class FoodService
{
    use Food;

    /**
     * @return array
     * @throws Throwable
     */
    public function get(): array
    {
        return request()->get('food')->details();
    }

}
