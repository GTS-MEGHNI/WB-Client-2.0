<?php

namespace Modules\Payment\Http\Controllers;

use App\Dictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payment\Http\Requests\BasePaymentRequest;
use Modules\Payment\Http\Requests\GetPaymentRequest;
use Modules\Payment\Services\BasicPaymentService;
use Modules\Payment\Services\PaymentService;
use Throwable;

class PaymentController extends Controller
{

    public function get(GetPaymentRequest $request, PaymentService $service): JsonResponse
    {
        return response()->json($service->get($request->validated()['id']));
    }

    /**
     * @throws Throwable
     */
    public function ccp(BasePaymentRequest $request, BasicPaymentService $service): Response
    {
        $service->save($request->validated(), Dictionary::CCP);
        return response()->noContent();
    }

    /**
     * @throws Throwable
     */
    public function baridimob(BasePaymentRequest $request, BasicPaymentService $service): Response
    {
        $service->save($request->validated(), Dictionary::BARIDIMOB);
        return response()->noContent();
    }


}
