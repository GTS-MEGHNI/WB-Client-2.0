<?php

namespace Modules\Food\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Food\Http\Requests\CreateRecipeRequest;
use Modules\Food\Http\Requests\GetRecipeRequest;
use Modules\Food\Services\Recipe\RecipeCreateService;
use Modules\Food\Services\Recipe\RecipeService;
use Throwable;

class RecipeController extends Controller
{
    /**
     * @param CreateRecipeRequest $request
     * @param RecipeCreateService $service
     * @return Response
     * @throws Throwable
     */
    public function create(CreateRecipeRequest $request, RecipeCreateService $service): Response
    {
        $service->create($request->validated());
        return response()->noContent();
    }

    /**
     * @param GetRecipeRequest $request
     * @param RecipeService $service
     * @return JsonResponse
     */
    public function get(GetRecipeRequest $request, RecipeService $service) : JsonResponse {
        return response()->json($service->get($request->validated()));
    }
}
