<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Authentication\Events\AccountRegistered;
use Modules\Authentication\Events\UserLogged;
use Modules\Authentication\Http\Requests\FacebookAuthRequest;
use Modules\Authentication\Services\FacebookAuthService;
use ParagonIE\Paseto\Exception\PasetoException;
use Throwable;

class FacebookAuthController extends Controller
{
    /**
     * @throws PasetoException
     * @throws Throwable
     */
    public function signup(FacebookAuthRequest $request, FacebookAuthService $facebookAuthService): JsonResponse
    {
        $facebookAuthService->recordUser($request->validated()['token']);
        event(new AccountRegistered($facebookAuthService->user));
        return response()->json($facebookAuthService->respondWithToken());
    }

    /**
     * @throws PasetoException
     * @throws Throwable
     */
    public function login(FacebookAuthRequest $request, FacebookAuthService $facebookAuthService): JsonResponse
    {
        $facebookAuthService->authenticate($request->validated()['token']);
        event(new UserLogged($facebookAuthService->user));
        return response()->json($facebookAuthService->respondWithToken());
    }

    /**
     * @throws Throwable
     */
    public function quickAuth(FacebookAuthRequest $request, FacebookAuthService $service): JsonResponse
    {
        return response()->json($service->quickAuth($request->validated()['token']));
    }

}
