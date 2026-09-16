<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Quote;
use OpenApi\Attributes as OA;

class QuotesController extends Controller
{

    #[OA\Get(
        path: '/api/v1/quotes/show',
        tags: ['Quote'],
        summary: "Get today's quote for the church",
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/QuoteResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function index()
    {
        $start_today = date('Y-m-d 00:00:00');
        $end_today = date('Y-m-d 23:59:59');

        $quote = Quote::where('church_id', Auth::user()->church_id)->where([['publish_on', '>', $start_today], ['publish_on', '<=', $end_today]])->latest()->first();


        $array = [];

        if ($quote) {
            $array['id']                =  $quote->id;
            $array['image']             =  $quote->image === null ? null : $quote->ImagePath;
            $array['text']              =  $quote->text === null ? null : $quote->text;
            $array['tamil_quotes']      =  $quote->tamil_quotes === null ? null : $quote->tamil_quotes;
            $array['english_quotes']    =  $quote->english_quotes === null ? null : $quote->english_quotes;
            $array['publish_on']        =  $quote->publish_on === null ? null : date('d-m-Y H:i:s', strtotime($quote->publish_on));
        }

        return $array;
    }
}
