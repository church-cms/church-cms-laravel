<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebGuestAuth
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->guest(route('web.guest.login'))
                ->with('auth_notice', 'Please register or login to submit.');
        }

        return $next($request);
    }
}
