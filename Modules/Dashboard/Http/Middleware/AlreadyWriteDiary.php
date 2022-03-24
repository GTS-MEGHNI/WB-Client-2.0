<?php

namespace Modules\Dashboard\Http\Middleware;

use App\Exceptions\DiaryException;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Modules\Dashboard\Services\DiaryService;
use Modules\Subscription\Traits\Order;
use Throwable;

class AlreadyWriteDiary
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
        throw_if(!(new DiaryService())->canWrite(), DiaryException::class);
        return $next($request);
    }
}
