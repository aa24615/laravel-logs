<?php

namespace Zyan\LaravelLogs\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
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

        try {
            $logs = new Logs();
            $logs->request()->response($response)->write();
        } catch (\Exception $e) {
            Log::error($e->getMessage().' '.$e->getFile().":".$e->getLine());
        }

        return $response;
    }
}
