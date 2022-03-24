<?php

namespace Modules\Dashboard\Http\Middleware;

use App\Exceptions\BodyProgressException;
use Closure;
use Illuminate\Http\Request;
use Modules\Dashboard\Services\ProgressService;
use Modules\Subscription\Traits\Order;
use Throwable;

class AlreadyWriteBodyProgress
{
    use Order;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        throw_if(!(new ProgressService())->canWrite(), BodyProgressException::class);
        return $next($request);
    }
}
