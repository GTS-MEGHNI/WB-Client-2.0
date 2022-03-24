<?php

namespace Modules\Authentication\Services;

use Carbon\Carbon;
use Modules\Authentication\Entities\UsersPerYearModel;

class UserIDGeneratorService
{

    public function generateID(): string
    {
        $count = $this->getUsersCountPerYear();
        $current_year = Carbon::now()->format('y');

        if ($count < 10)
            $user_id = $current_year . 'U' . "00000" . $count;
        else if ($count < 100)
            $user_id = $current_year . 'U' . "0000" . $count;
        else if ($count < 1000)
            $user_id = $current_year . 'U' . "000" . $count;
        else if ($count < 10000)
            $user_id = $current_year . 'U' . "00" . $count;
        else if ($count < 100000)
            $user_id = $current_year . 'U' . "0" . $count;
        else
            $user_id = $current_year . 'U' . $count;

        $this->updateYearCount();

        return $user_id;
    }

    public function getUsersCountPerYear(): int
    {
        $row = UsersPerYearModel::where('year', '=', Carbon::now()->year)->first();

        if ($row == null) {
            $this->addNewYear();
            return 0;
        } else
            return $row->count;

    }

    public function addNewYear(): void
    {
        UsersPerYearModel::create([
            'year' => Carbon::now()->year,
            'count' => 0
        ]);
    }

    public function updateYearCount(): void
    {
        UsersPerYearModel::where('year', '=', Carbon::now()->year)->increment('count');
    }

}
