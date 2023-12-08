<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FrameMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->header('Content-Security-Policy', 'frame-ancestors http://127.0.0.1:8000');
        return $response;
    }
}
