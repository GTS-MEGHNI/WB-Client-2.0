<?php

namespace Modules\Subscription\Http\Middleware;

use App\Exceptions\AlreadyAppliedException;
use App\Exceptions\ClassroomFullException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Subscription\Traits\Order;
use Throwable;

class CanSubscribeMiddleware
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
        throw_if($this->classroomIsFull(), ClassroomFullException::class);
        throw_if($this->alreadyApplied(), AlreadyAppliedException::class);
        return $next($request);
    }

    private function classroomIsFull(): bool
    {
        dd(DB::table('classroom.students')->count() === env('MAX_STUDENTS'));
        return DB::table('classroom.students')->count() === env('MAX_STUDENTS');
    }

    /**
     * @return bool
     * @throws Throwable
     */
    private function alreadyApplied(): bool
    {
        $order = $this->getUserLatestOrder();
        if ($order === null)
            return false;
        return !in_array($order->status, config('subscription.inactive_states'));
    }

}
