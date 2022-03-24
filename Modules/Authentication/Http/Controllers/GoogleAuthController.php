<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Authentication\Events\AccountRegistered;
use Modules\Authentication\Events\UserLogged;
use Modules\Authentication\Http\Requests\GoogleAuthRequest;
use Modules\Authentication\Services\GoogleAuthService;
use ParagonIE\Paseto\Exception\PasetoException;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * @throws PasetoException|Throwable
     */
    public function signup(GoogleAuthRequest $request, GoogleAuthService $googleAuthService): JsonResponse
    {
        $googleAuthService->recordUser($request->validated()['token']);
        event(new AccountRegistered($googleAuthService->user));
        return response()->json($googleAuthService->respondWithToken());
    }

    /**
     * @throws PasetoException|Throwable
     */
    public function login(GoogleAuthRequest $request, GoogleAuthService $googleAuthService): JsonResponse
    {
        $googleAuthService->authenticate($request->validated()['token']);
        event(new UserLogged($googleAuthService->user));
        return response()->json($googleAuthService->respondWithToken());
    }

    /**
     * @throws Throwable
     */
    public function quickAuth(GoogleAuthRequest $request, GoogleAuthService $service): JsonResponse
    {
        return response()->json($service->quickAuth($request->validated()['token']));
    }


}
