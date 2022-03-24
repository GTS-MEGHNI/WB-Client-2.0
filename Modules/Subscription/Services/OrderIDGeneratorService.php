<?php

namespace Modules\Subscription\Services;

use Carbon\Carbon;
use Modules\Subscription\Entities\OrderPerYearModel;

class OrderIDGeneratorService
{
    private int $orders_count;
    private ?OrderPerYearModel $year_row;

    public function generateID(): string
    {
        $this->geOrdersCountFromDB();
        $year = Carbon::now()->format('y');
        $symbol = 'CO';
        if ($this->orders_count < 10)
            $order_id = $year . $symbol . '000' . $this->orders_count;
        else if ($this->orders_count < 100)
            $order_id = $year . $symbol . '00' . $this->orders_count;
        else if ($this->orders_count < 1000)
            $order_id = $year . $symbol . '0' . $this->orders_count;
        else
            $order_id = $year . 'WB' . $this->orders_count;

        $this->year_row->increment('count');
        $this->year_row->save();
        return $order_id;
    }

    private function geOrdersCountFromDB(): void
    {
        $this->year_row = OrderPerYearModel::where(['year' =>  Carbon::now()->year])->first();
        if ($this->year_row == null)
            $this->addNewYear();
        $this->orders_count = $this->year_row->count;
    }

    public function addNewYear(): void
    {
        $this->year_row = OrderPerYearModel::create([
            'year' => Carbon::now()->year,
            'count' => 0
        ]);
    }


}
