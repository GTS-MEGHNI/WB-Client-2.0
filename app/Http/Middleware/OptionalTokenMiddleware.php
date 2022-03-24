<?php

namespace App\Http\Middleware;

use App\Auth;
use Closure;
use Illuminate\Http\Request;
use ParagonIE\Paseto\Exception\PasetoException;

class OptionalTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws PasetoException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();
        if($token !== null) {
            abort_if($token == null || Auth::errorInToken($token), 401);
            $request->request->add(['user_id' => Auth::getUserIDFromToken($token)]);
        }
        return $next($request);
    }
}
