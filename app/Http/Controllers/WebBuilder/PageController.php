<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private function navData(): array
    {
        $allPages = Page::with('pageCategory')
                        ->where('status', 1)
                        ->orderBy('menu_order')
                        ->orderBy('page_name')
                        ->get();

        $grouped = $allPages->groupBy(fn($p) => optional($p->pageCategory)->name ?? 'General');

        return [$allPages, $grouped];
    }

    public function index(Request $request)
    {
        [$allPages, $grouped] = $this->navData();

        // Active page: ?slug= param, otherwise first page
        $activeSlug = $request->query('slug');
        $activePage = $activeSlug
            ? $allPages->firstWhere('slug', $activeSlug)
            : $allPages->first();

        return view('theme::page_index', compact('grouped', 'activePage'));
    }

    public function show($categorySlug, $pageSlug)
    {
        [$allPages, $grouped] = $this->navData();

        $page = $allPages->first(function ($p) use ($categorySlug, $pageSlug) {
            return $p->slug === $pageSlug
                && optional($p->pageCategory)->slug === $categorySlug;
        });

        if (! $page) {
            abort(404);
        }

        $activePage = $page;

        return view('theme::page', compact('page', 'grouped', 'activePage'));
    }
}
