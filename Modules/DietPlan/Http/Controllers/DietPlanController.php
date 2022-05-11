<?php

namespace Modules\DietPlan\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\DietPlan\Services\DietPlanService;
use Throwable;

class DietPlanController extends Controller
{
    /**
     * @param DietPlanService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function getPlan(DietPlanService $service): JsonResponse
    {
        return response()->json($service->getPlan());
    }

    public function markAsConsumed(DietPlanService $service): JsonResponse
    {
        return response()->json($service->markAsConsumed());
    }

    public function markAsNotConsumed(DietPlanService $service): JsonResponse
    {
        return response()->json($service->markAsNotConsumed());
    }

    public function getFood(DietPlanService $service): JsonResponse
    {
        return response()->json($service->getFood());
    }

    /**
     * @param DietPlanService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function getConfig(DietPlanService $service): JsonResponse
    {
        return response()->json($service->getConfig());
    }

    public function cache(DietPlanService $service) {
        $service->cacheCalendar();
    }
}
