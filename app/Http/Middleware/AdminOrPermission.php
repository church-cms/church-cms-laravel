<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Laratrust\Middleware\LaratrustPermission;

class AdminOrPermission extends LaratrustPermission
{
    public function handle($request, Closure $next, $permissions, $team = null, $options = '')
    {
        $user = Auth::user();

        // Church admins bypass all permission checks
        if ($user && $user->usergroup_id == 3) {
            return $next($request);
        }

        return parent::handle($request, $next, $permissions, $team, $options);
    }
}
