<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Authentication\Events\UserLogged;
use Modules\Authentication\Http\Requests\AppAuthRequest;
use Modules\Authentication\Services\AppAuthService;
use Throwable;

class LoginController extends Controller
{
    /**
     * @param AppAuthRequest $request
     * @param AppAuthService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function login(AppAuthRequest $request, AppAuthService $service): JsonResponse
    {
        $service->authenticate($request->validated());
        event(new UserLogged($service->user));
        return response()->json($service->respondWithToken());
    }
}
