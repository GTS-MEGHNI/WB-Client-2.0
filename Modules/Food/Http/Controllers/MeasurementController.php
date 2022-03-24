<?php

namespace Modules\Food\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Food\Http\Requests\CreateMeasurementRequest;
use Modules\Food\Services\MeasurementService;

class MeasurementController extends Controller
{
    public function create(CreateMeasurementRequest $request, MeasurementService $service): Response
    {
        $service->create($request->validated());
        return response()->noContent();
    }
}
