<?php

namespace Modules\Food\Services;

use Modules\Food\Entities\FactModel;
use Modules\Food\Entities\MeasurementModel;

class MeasurementService
{
    public function create(array $payload)
    {
        $measurement = MeasurementModel::forceCreate([
            'food_id' => $payload['foodID'],
            'measure_type' => $payload['measurement']['quantity']['unit'],
            'quantity' => $payload['measurement']['quantity']['amount'],
            'weight' => $payload['measurement']['weight']['amount'],
            'weight_unit' => $payload['measurement']['weight']['unit'],
        ]);
        FactModel::forceCreate([
            'measurement_id' => $measurement->id,
            'calories' =>  $payload['measurement']['facts']['calories']['amount'],
            'calories_unit' =>  $payload['measurement']['facts']['calories']['unit'],
            'carbs' =>  $payload['measurement']['facts']['carbs']['amount'],
            'carbs_unit' =>  $payload['measurement']['facts']['carbs']['unit'],
            'protein' =>  $payload['measurement']['facts']['protein']['amount'],
            'protein_unit' =>  $payload['measurement']['facts']['protein']['unit'],
            'fat' =>  $payload['measurement']['facts']['fat']['amount'],
            'fat_unit' =>  $payload['measurement']['facts']['fat']['unit'],
        ]);
    }
}
