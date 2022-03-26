<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AlreadyAppliedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'canSubscribe' => false,
            'message' => 'ALREADY_SUBSCRIBED'
        ]);
    }
}
