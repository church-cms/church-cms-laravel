<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::withCount('photos')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);

        return view('theme::gallery_index', compact('galleries'));
    }

    public function show($id)
    {
        $gallery = Gallery::with('photos')->findOrFail($id);

        return view('theme::gallery', compact('gallery'));
    }
}
