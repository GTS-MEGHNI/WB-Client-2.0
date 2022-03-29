<?php

namespace Modules\Plans\services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Plans\Entities\ProgramModel;

class PlanService
{

    public function list(): array
    {
        return Arr::collapse([
            ['list' => ProgramModel::all()->toArray()],
            ['placesLeft' => env('MAX_STUDENTS') - DB::table('classroom.students')->count()]
        ]);
    }

}
