<?php

namespace App\Http\Middleware;

use Closure;

class MustBeSiteSubAdmin
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
        if(\Auth::user()->usergroup_id==2)
        {
            return $next($request);
        }

        abort(404);
    }
}