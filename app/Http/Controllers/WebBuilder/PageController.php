<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private function navData(): array
    {
        $allPages = Page::with('pageCategory')
            ->where('pages.status', 1)
            ->join('page_categories', 'pages.category_id', '=', 'page_categories.id')
            ->orderBy('page_categories.sort_order')
            ->orderBy('pages.menu_order')
            ->orderBy('pages.page_name')
            ->select('pages.*')
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
