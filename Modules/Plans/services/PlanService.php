<?php

namespace Modules\Plans\services;

use Modules\Plans\Entities\ProgramModel;

class PlanService
{

    public function list(): array
    {
        return ProgramModel::all()->toArray();
    }

}
