<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;

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

        return view('theme::faq_index', compact('categories'));
    }
}
