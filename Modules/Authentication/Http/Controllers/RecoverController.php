<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Authentication\Http\Requests\RecoverChallengeRequest;
use Modules\Authentication\Http\Requests\RecoverResetPasswordRequest;
use Modules\Authentication\Http\Requests\RecoverSubmitMailRequest;
use Modules\Authentication\Services\RecoverService;
use Throwable;

class RecoverController extends Controller
{

    /**
     * @param RecoverSubmitMailRequest $request
     * @param RecoverService $service
     * @return JsonResponse
     * @throws Throwable
     */
    public function sendRecoverPasscode(RecoverSubmitMailRequest $request, RecoverService $service): JsonResponse
    {
        $service->sendPasscode($request->validated());
        return response()->json([
            'token' => $service->token
        ]);
    }

    /**
     * @param RecoverService $service
     * @return void
     */
    public function resendRecoverPasscode(RecoverService $service)
    {
        $service->resendPasscode();
    }

    /**
     * @param RecoverChallengeRequest $request
     * @return Response
     * @noinspection PhpUnusedParameterInspection
     */
    public function challenge(RecoverChallengeRequest $request): Response
    {
        return response()->noContent();
    }

    /**
     * @throws Throwable
     */
    public function resetPassword(RecoverResetPasswordRequest $request, RecoverService $recoverPasswordService): Response
    {
        $recoverPasswordService->updatePassword($request->validated());
        return response()->noContent();
    }

}
