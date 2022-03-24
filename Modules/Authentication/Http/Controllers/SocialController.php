<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Authentication\Http\Requests\FacebookAuthRequest;
use Modules\Authentication\Http\Requests\GoogleAuthRequest;
use Modules\Authentication\Services\FacebookService;
use Modules\Authentication\Services\GoogleService;
use Throwable;

class SocialController extends Controller
{
    /**
     * @param GoogleAuthRequest $request
     * @param GoogleService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function google(GoogleAuthRequest $request, GoogleService $service) : JsonResponse {
        $service->authenticate($request->validated());
        return response()->json($service->respondWithToken());
    }

    /**
     * @param FacebookAuthRequest $request
     * @param FacebookService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function facebook(FacebookAuthRequest $request, FacebookService $service) : JsonResponse {
        $service->authenticate($request->validated());
        return response()->json($service->respondWithToken());
    }

}
