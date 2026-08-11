<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\Widget;

class FaqController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::with(['faq' => function ($q) {
            $q->where('status', 1)->orderBy('order');
        }])
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $widgets = Widget::where('page', 'faq')
            ->orderBy('display_order')
            ->get();

        $topwidget = $widgets->where('position', 'top');
        $bottomwidget = $widgets->where('position', 'bottom');

        return view('theme::faq_index', compact('categories', 'topwidget', 'bottomwidget'));
    }
}
