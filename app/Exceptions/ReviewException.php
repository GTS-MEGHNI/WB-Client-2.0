<?php

namespace App\Exceptions;

use App\Responses;
use Exception;

class ReviewException extends Exception
{
    public function render() {
        return response()->json(Responses::emptyDebugResponseError(Responses::GENERAL_ERROR_FR));
    }
}
