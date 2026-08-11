<?php

namespace App\Http\Middleware;

use App\Models\Church;
use App\Models\ChurchDetail;
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

        $churchdetail = [];
        if ($church) {
            $churchdetail = ChurchDetail::where('church_id', $church->id)
                ->pluck('meta_value', 'meta_key')
                ->map(fn($v) => $v === '-' ? null : $v)
                ->toArray();
        }

        View::share('_church', $church);
        View::share('_churchdetail', $churchdetail);
        $request->attributes->set('_church', $church);
        return $next($request);
    }
}
