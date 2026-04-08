<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Sermon;

class SermonController extends Controller
{
    public function index()
    {
        $grouped = Sermon::with('user')
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->groupBy('user_id');

        return view('theme::sermon_index', compact('grouped'));
    }

    public function show($id)
    {
        $sermon = Sermon::with(['user', 'sermonlinks'])->findOrFail($id);

        return view('theme::sermon', compact('sermon'));
    }
}
