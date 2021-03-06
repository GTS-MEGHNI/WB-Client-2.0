<?php

namespace Modules\Subscription\Http\Middleware;

use App\Exceptions\CannotDeleteException;
use Closure;
use Illuminate\Http\Request;
use App\Dictionary;
use Modules\Subscription\Traits\Order;
use Throwable;

class CanDeleteOrder
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
        throw_if($order === null ||
            !in_array($order->status, [Dictionary::CANCELED_ORDER,
                Dictionary::TERMINATED_ORDER, Dictionary::REJECTED_ORDER]),
            CannotDeleteException::class);
        $request->request->add(['order' => $order]);
        return $next($request);
    }
}
