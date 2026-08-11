<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Sermon;

class SermonController extends Controller
{
    public function index()
    {
        // $grouped = Sermon::with('user')
        //                  ->orderBy('created_at', 'desc')
        //                  ->get()
        //                  ->groupBy('user_id');

        $grouped = Sermon::with(['user', 'sermonlinks'])
            ->whereHas('sermonlinks')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');


        return view('theme::sermon_index', compact('grouped'));
    }

    public function show($id)
    {
        $sermon = Sermon::with(['user.userprofile', 'sermonlinks'])->findOrFail($id);

        $recommended = Sermon::with('user')
            ->where('church_id', $sermon->church_id)
            ->where('id', '!=', $sermon->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('theme::sermon', compact('sermon', 'recommended'));
    }
}
