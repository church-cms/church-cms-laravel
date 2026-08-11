<?php

namespace App\Http\Controllers;

use App\Http\Resources\Faq as FaqResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Faq;

/**
 * FaqController
 *
 * Manages frequently asked questions for the church.
 * Displays FAQ listings organized by categories for both public and authenticated users.
 *
 * @package App\Http\Controllers
 */
class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $faq  = Faq::where([['church_id',Auth::user()->church_id],['status', 1]])->with('faqCategory')->orderBy('faq_category_id')->orderBy('order')->get();

        $faq= FaqResource::collection($faq);

        return json_encode($faq);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/faq');
    }
}
