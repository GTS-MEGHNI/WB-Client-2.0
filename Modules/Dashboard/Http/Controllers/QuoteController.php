<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Services\QuoteService;
use Throwable;

class QuoteController extends Controller
{
    /**
     * @param QuoteService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function list(QuoteService $service): JsonResponse
    {
        return response()->json($service->list());
    }

}
