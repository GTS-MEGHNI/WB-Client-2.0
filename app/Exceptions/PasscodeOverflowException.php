<?php

namespace App\Exceptions;

use App\Responses;
use Exception;
use Illuminate\Http\JsonResponse;

class PasscodeOverflowException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json(Responses::emptyDebugResponseError(
            Responses::PASSCODE_OVERFLOW
        ));
    }}
