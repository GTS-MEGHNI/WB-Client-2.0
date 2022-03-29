<?php

namespace Modules\Plans\services;

use Illuminate\Support\Facades\DB;
use Modules\Plans\Entities\ProgramModel;

class PlanService
{

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function list(): array
    {
        return [
            'list' => ProgramModel::all()->toArray(),
            'placesLeft' => env('MAX_STUDENTS') - DB::table('classroom.students')->count()
        ];
    }

}
