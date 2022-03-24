<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Subscription\Traits\Order;
use Throwable;

class HaveOnGoingSubscription
{
    use Order;
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @throws Throwable
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $order = $this->getUserLatestOrder();
        abort_if($order == null || in_array($order->status, config('subscription.inactive_states')),
            401);
        return $next($request);
    }
}
