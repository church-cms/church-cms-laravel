<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Config;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        $maintenance = Config::get('settings.maintenance');

        //dd($maintenance);

        // Allow admin area
        if ($request->is('admin/*') || $request->is('admin') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        if ($maintenance) {
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
