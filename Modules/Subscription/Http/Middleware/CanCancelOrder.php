<?php

namespace Modules\Subscription\Http\Middleware;

use App\Exceptions\CannotCancelException;
use Closure;
use Illuminate\Http\Request;
use App\Dictionary;
use Modules\Subscription\Traits\Order;
use Throwable;

class CanCancelOrder
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
        $order = $this->getUserLatestOrder();
        throw_if($order == null || $order->status != Dictionary::PENDING_ORDER,
            CannotCancelException::class);
        return $next($request);
    }
}
