<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\Help as HelpResource;
use App\Http\Controllers\Controller;
use App\Models\Help;

class HelpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($church_id)
    {
        //
        $help = Help::where([['church_id',$church_id],['status','approve']])->latest()->get();
        
        $help = HelpResource::collection($help);

        return $help;
    }
}