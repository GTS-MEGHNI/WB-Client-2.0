<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class AlreadyAppliedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'canSubscribe' => false,
            'message' => Responses::ALREADY_APPLIED
        ]);
    }
}
