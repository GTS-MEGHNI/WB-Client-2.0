<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class SizeNotFoundException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(Responses::emptyDebugResponseError(Responses::SIZE_NOT_FOUND));

    }
}
