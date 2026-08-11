<?php

namespace App\Http\Middleware;

use Closure;

class MustBePreacher
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
        if(\Auth::user()->usergroup_id == 6)
        {
            return $next($request);
        }

        if(\Auth::user()->usergroup_id == 3)
        {
            return redirect('/admin/dashboard');
        }
      
        if(\Auth::user()->usergroup_id == 1)
        {
            return redirect('/portal');
        }

        
       abort(404);
    }
}