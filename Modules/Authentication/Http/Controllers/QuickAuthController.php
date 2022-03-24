<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Authentication\Http\Requests\CreatePasswordRequest;
use Modules\Authentication\Http\Requests\QuickAuthCreateAccountRequest;
use Modules\Authentication\Http\Requests\QuickAuthChallengeRequest;
use Modules\Authentication\Services\PasscodeService;
use Modules\Authentication\Services\QuickAuthService;
use Throwable;

class QuickAuthController extends Controller
{
    /**
     * @throws Throwable
     */
    public function sendPasscode(QuickAuthCreateAccountRequest $request, QuickAuthService $service)
    : JsonResponse
    {
        return response()->json($service->checkAccountExistence($request->validated()));
    }

    /**
     * @param QuickAuthChallengeRequest $request
     * @param QuickAuthService $service
     * @return Response|JsonResponse
     * @throws Throwable
     * @noinspection PhpUnusedParameterInspection
     */
    public function validateAccount(QuickAuthChallengeRequest $request, QuickAuthService $service)
    : Response|JsonResponse
    {
        return $service->verifyAccount();
    }


    public function resendPasscode(PasscodeService $service): Response
    {
        $service->resendActivationPasscode();
        return response()->noContent();
    }

    /**
     * @param CreatePasswordRequest $request
     * @param QuickAuthService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function createPassword(CreatePasswordRequest $request, QuickAuthService $service): JsonResponse
    {
        $service->record($request->validated());
        return response()->json($service->respondWithToken());
    }

}
