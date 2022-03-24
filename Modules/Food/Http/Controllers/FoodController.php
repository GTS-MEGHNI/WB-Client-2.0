<?php

namespace Modules\Food\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Food\Http\Requests\GetFoodRequest;
use Modules\Food\Http\Requests\SearchFoodRequest;
use Modules\Food\Services\FoodSearchService;
use Modules\Food\Services\FoodService;
use Throwable;

class FoodController extends Controller
{
    public function search(SearchFoodRequest $request, FoodSearchService $service): JsonResponse
    {
        return response()->json($service->getFood($request->validated()));
    }

    /**
     * @throws Throwable
     */
    public function get(GetFoodRequest $request, FoodService $service): JsonResponse
    {
        return response()->json($service->get());
    }


}
