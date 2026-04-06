<?php

namespace App\Http\Middleware;

use Closure;

class MustBeChurchAdmin
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
        if(\Auth::user()->usergroup_id == 3 || \Auth::user()->usergroup_id == 4)
        {
            return $next($request);
        }

        if(\Auth::user()->usergroup_id == 1)
        {
            return redirect('/portal');
        }

        if(\Auth::user()->usergroup_id == 6)
        {
            return redirect('/preacher/dashboard');
        }

        abort(404);
    }
}
