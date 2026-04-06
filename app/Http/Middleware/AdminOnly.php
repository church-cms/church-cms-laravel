<?php

namespace App\Http\Middleware;

use Closure;

class AdminOnly
{
    /**
     * Restrict access to church admin only (usergroup_id == 3).
     * Sub-admins are blocked from admin-management sections.
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->usergroup_id == 3) {
            return $next($request);
        }

        abort(403);
    }
}
