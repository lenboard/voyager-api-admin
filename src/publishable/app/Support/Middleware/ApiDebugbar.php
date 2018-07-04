<?php

namespace App\Support\Middleware;

use Closure;
use App\Support\DataCollector\ApiCollector;

class ApiDebugbar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // add ravel data collector
        $debugbar = app()->make('debugbar');
        $debugbar->addCollector(new ApiCollector);

        return $next($request);
    }
}
