<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class ClassroomFullException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'canSubscribe' => false,
            'message' => Responses::CLASSROOM_FULL
        ]);
    }
}
