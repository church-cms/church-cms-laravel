<?php

namespace App\Http\Middleware;

use App\Models\Church;
use Illuminate\Support\Facades\View;
use Closure;

class WebCmsContext
{
    public function handle($request, Closure $next)
    {
        try {
            $church = Church::first();
        } catch (\Exception $e) {
            $church = null;
        }
        View::share('_church', $church);
        $request->attributes->set('_church', $church);
        return $next($request);
    }
}
