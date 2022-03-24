<?php

namespace Modules\Authentication\Http\Middleware;

use App\Exceptions\SessionExpiredException;
use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Services\ShortTermTokenService;
use Throwable;

class ShortTermTokenValidation
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->bearerToken();
        abort_if($token === null || ShortTermTokenService::errorInToken($token), 401);
        throw_if(ShortTermTokenService::tokenExpired($token),
            SessionExpiredException::class);
        return $next($request);
    }
}
