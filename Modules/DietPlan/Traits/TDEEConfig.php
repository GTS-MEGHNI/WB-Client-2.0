<?php

namespace Modules\DietPlan\Traits;


use Modules\DietPlan\Entities\TDEEDistModel;

trait TDEEConfig
{

    /**
     * @param string $order_id
     * @return array
     */
    private function getTDEEDist(string $order_id): array
    {
        return TDEEDistModel::where(['order_id' => $order_id])
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }

}
