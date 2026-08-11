<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\ShowSermonLink as ShowSermonLinkResource;
use App\Http\Resources\API\Guest\Sermon as SermonResource;
use App\Http\Controllers\Controller;
use App\Models\SermonLink;
use App\Models\Sermon;

class SermonsController extends Controller
{
    public function index($church_id)
    {
        $sermon = Sermon::where('church_id',$church_id)->latest()->paginate(10);
        
        $sermon = SermonResource::collection($sermon);
        
        return $sermon;
    }

	public function show($church_id,$sermons_id)
    {
        $links = SermonLink::with('sermons')->where([['sermons_id',$sermons_id],['church_id',$church_id]])->paginate(10);
        
        $links = ShowSermonLinkResource::collection($links);
        
        return $links;
    }
}