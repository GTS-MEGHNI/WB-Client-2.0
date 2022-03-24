<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Authentication\Events\AccountRegistered;
use Modules\Authentication\Http\Requests\AppSignRequest;
use Modules\Authentication\Http\Requests\QuickAuthChallengeRequest;
use Modules\Authentication\Services\AppAuthService;
use Modules\Authentication\Services\PasscodeService;
use Modules\Authentication\Services\RegisterService;
use Throwable;

class RegisterController extends Controller
{
    /**
     * @param AppSignRequest $request
     * @param RegisterService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function sendActivationPasscode(AppSignRequest $request, RegisterService $service): JsonResponse
    {
        $service->sendPasscode($request->validated());
        return response()->json([
            'token' => $service->token
        ]);
    }

    public function resendActivationPasscode(RegisterService $service): Response
    {
        $service->resendPasscode();
        return response()->noContent();
    }


    /**
     * @param QuickAuthChallengeRequest $request
     * @param RegisterService $service
     * @return JsonResponse
     * @throws Throwable
     * @noinspection PhpUnusedParameterInspection
     */
    public function validateAccount(QuickAuthChallengeRequest $request, RegisterService $service): JsonResponse
    {
        $service->record();
        event(new AccountRegistered($service->user));
        return response()->json($service->respondWithToken());
    }

}
