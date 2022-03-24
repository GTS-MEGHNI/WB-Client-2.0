<?php

namespace App\Http\Middleware;

use App\Dictionary;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Dashboard\Entities\BodyProgressModel;
use Modules\Dashboard\Entities\DiaryActivityModel;
use Modules\Dashboard\Entities\DiaryFeelingModel;
use Modules\Dashboard\Entities\DiaryModel;
use Modules\Subscription\Traits\Order;
use Throwable;

class HasActivePlan
{
    use Order;

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $order = $this->getUserLatestOrder();
        abort_if($order === null || $order->status != Dictionary::ACTIVE_ORDER, 401);
        $request->request->add(['order' => $order]);
        return $next($request);
    }
}
