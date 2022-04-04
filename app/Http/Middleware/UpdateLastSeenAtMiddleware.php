<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Utility;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateLastSeenAtMiddleware
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
        if ($request->has('user_id')) {
            $user = User::find(Utility::getUserId());
            $now = Carbon::now();
            User::unguard();
            if ($now->diffInMinutes($user->last_seen_at) < 5) {
                User::where(['id' => $request->get('user_id')])->update([
                    'status' => 'connected',
                    'last_seen_at' => Carbon::now(),
                    'updated_at' => DB::raw('updated_at'),
                    'spending_time' => $user->spending_time + ($now->diffInSeconds($user->last_seen_at))
                ]);
            }
            else
                User::where(['id' => $request->get('user_id')])->update([
                    'status' => 'connected',
                    'last_seen_at' => Carbon::now(),
                    'updated_at' => DB::raw('updated_at')
                ]);
        }
        User::reguard();
        return $next($request);
    }
}
