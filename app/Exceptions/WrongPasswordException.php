<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class WrongPasswordException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(Responses::emptyDebugResponseError(Responses::WRONG_PASSWORD));
    }
}
