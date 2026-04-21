<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\API\Angular\FaqCategory as FaqCategoryResource;
use App\Http\Resources\API\Angular\Faq as FaqResource;
use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\Church;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($slug)
    {
        //
        $church = Church::where('slug', '=', $slug)->first();

        $faq = FaqCategory::where([['church_id', $church->id], ['status', 1]])->get();

        $faq = FaqCategoryResource::collection($faq);

        return $faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        //
        $church = Church::where('slug', '=', $slug)->first();

        $faq = Faq::where([['church_id', $church->id], ['status', 1]])->orderBy('faq_category_id')->orderBy('order')->get();

        $faq = FaqResource::collection($faq);

        return $faq;
    }
}
