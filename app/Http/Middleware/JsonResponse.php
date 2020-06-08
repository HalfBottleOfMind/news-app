<?php

namespace App\Http\Middleware;

use Closure;

class JsonResponse
{
    /**
     * Forces JSON Accept headers to always return a JSON data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
