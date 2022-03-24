<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class TargetNotFoundException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(Responses::DebugResponseError(Responses::TARGET_NOT_FOUND,
            Responses::GENERAL_ERROR_FR));
    }
}
