<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;

class BulletinController extends Controller
{
    public function index()
    {
        $bulletins = Bulletin::orderBy('year', 'desc')
                             ->orderBy('month', 'desc')
                             ->orderBy('week', 'desc')
                             ->paginate(12);

        return view('theme::bulletin_index', compact('bulletins'));
    }

    public function show($id)
    {
        $bulletin = Bulletin::findOrFail($id);

        $related = Bulletin::where('church_id', $bulletin->church_id)
                           ->where('year', $bulletin->year)
                           ->where('id', '!=', $bulletin->id)
                           ->orderBy('month', 'desc')
                           ->orderBy('week', 'desc')
                           ->limit(4)
                           ->get();

        return view('theme::bulletin', compact('bulletin', 'related'));
    }
}
