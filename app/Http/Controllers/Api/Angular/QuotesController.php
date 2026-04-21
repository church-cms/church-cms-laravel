<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\API\Quotes as QuotesResource;
use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Quote;

class QuotesController extends Controller
{

    public function showQuotes($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();

        $quotes = Quote::where('church_id', $church->id)->get();

        $quotes = QuotesResource::collection($quotes);

        return $quotes;
    }
}
