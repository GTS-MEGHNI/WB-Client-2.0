<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ClassroomFullException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'canSubscribe' => false,
            'code' => 'PROGRAM_FULL'
        ]);
    }
}
