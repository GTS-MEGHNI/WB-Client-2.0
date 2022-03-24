<?php

namespace Modules\Authentication\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Entities\RegisterLog;

class QuickAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $record = RegisterLog::where([
            'email' => $request->request->get('email'),
            'verified' => true
        ])->where('created_at', '>', Carbon::now()->subMinutes(config('authentication.record_log_lifetime')))
            ->latest()
            ->first();
        abort_if($record == null, 401);
        $request->request->add(['row' => $record]);
        return $next($request);
    }
}
