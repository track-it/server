<?php

namespace Trackit\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RemoveUrlParam
{
    /**
     * This is really only a temporary solution for running Laravel in a sub-folder.
     * We're rewriting urls in nginx to index.php?$args, which means that the url
     * will be a query parameters, which screws with some of our logic.
     *
     * Here we simply try to find the request url and remove it from query parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        foreach ($request->all() as $key => $value) {
            $url = explode("?", $request->server('REQUEST_URI'))[0];
            if (strpos($url, $key) !== false) {
                $request->query->remove($key);
            }
        }

        return $next($request);
    }
}
