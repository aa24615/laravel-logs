<?php

namespace Zyan\LaravelLogs\Middleware;

use Closure;
use Zyan\LaravelLogs\Logs;

class RequestLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $logs = new Logs();
        $logs->request()->sql()->response()->write();

        return $response;
    }
}
