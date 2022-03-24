<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Subscription\Events\SubscriptionCancelled;
use Modules\Subscription\Events\SubscriptionCreated;
use Modules\Subscription\Http\Requests\CheckoutRequest;
use Modules\Subscription\Services\CheckoutService;
use Modules\Subscription\Services\SubscriptionService;
use Throwable;

class SubscriptionController extends Controller
{
    /**
     * @throws Throwable
     */
    public function create(CheckoutRequest $request, CheckoutService $service): Response
    {
        $service->record($request->validated());
        event(new SubscriptionCreated($service->order_id));
        return response()->noContent();
    }

    /**
     * @param SubscriptionService $applicationService
     * @return Response
     * @throws Throwable
     */
    public function cancel(SubscriptionService $applicationService): Response
    {
        $applicationService->cancel();
        event(new SubscriptionCancelled());
        return response()->noContent();
    }


    /**
     * @param SubscriptionService $applicationService
     * @return Response
     * @throws Throwable
     */
    public function delete(SubscriptionService $applicationService): Response
    {
        $applicationService->delete();
        return response()->noContent();
    }

    /**
     * @param SubscriptionService $subscriptionService
     * @return JsonResponse
     * @throws Throwable
     */
    public function get(SubscriptionService $subscriptionService): JsonResponse
    {
        return response()->json($subscriptionService->get());
    }

    public function verify(): JsonResponse
    {
        return response()->json([
            'canSubscribe' => true
        ]);
    }
}
