<?php

namespace Modules\Food\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Food\Http\Requests\CreateIngredientRequest;
use Modules\Food\Http\Requests\GetIngredientRequest;
use Modules\Food\Services\IngredientService;

class IngredientController extends Controller
{
    public function create(CreateIngredientRequest $request, IngredientService $service): Response
    {
        $service->create($request->validated());
        return response()->noContent();
    }

    /**
     * @param GetIngredientRequest $request
     * @param IngredientService $service
     * @return JsonResponse
     */
    public function get(GetIngredientRequest $request, IngredientService $service): JsonResponse
    {
        return response()->json($service->get($request->validated()));
    }

}
