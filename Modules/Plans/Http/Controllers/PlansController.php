<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Plans\services\PlanService;

class PlansController extends Controller
{
    public function list(PlanService $service) : JsonResponse{
        return response()->json($service->list());
    }
}
