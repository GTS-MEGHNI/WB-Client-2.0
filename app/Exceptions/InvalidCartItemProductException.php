<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class InvalidCartItemProductException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(Responses::DebugResponseError(Responses::INVALID_ITEM_PRODUCT,
            Responses::GENERAL_ERROR_FR));
    }
}
