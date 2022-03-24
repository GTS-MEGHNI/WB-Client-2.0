<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class BillingException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(Responses::DebugResponseError(Responses::FAILED_BILLING,
            Responses::GENERAL_ERROR_FR));
    }
}
