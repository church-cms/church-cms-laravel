<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $recentPosts   = Post::where('is_posted', 1)->where('status', 1)
                            ->orderBy('post_created_at', 'desc')
                            ->limit(6)->get();

        $featuredPages = Page::where('status', 1)
                            ->orderBy('created_at', 'desc')
                            ->limit(3)->get();

        return view('theme::home', compact('recentPosts', 'featuredPages'));
    }
}
