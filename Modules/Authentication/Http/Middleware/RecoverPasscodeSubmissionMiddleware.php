<?php

namespace Modules\Authentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Entities\RecoverLogModel;

class RecoverPasscodeSubmissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $row = RecoverLogModel::find(request()->bearerToken());
        abort_if($row === null || !$row->verified, 401);
        $request->request->add(['row' => $row]);
        return $next($request);
    }
}
