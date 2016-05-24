<?php

namespace Trackit\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RemoveUrlParam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        dd($request);
    }
}
