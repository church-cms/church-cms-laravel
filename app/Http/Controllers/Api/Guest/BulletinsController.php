<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\Bulletin as BulletinResource;
use App\Http\Controllers\Controller;
use App\Models\Bulletin;

class BulletinsController extends Controller
{
    public function index($church_id)
    {
        $bulletins = Bulletin::where('church_id',$church_id)->latest()->paginate(10);

        $bulletins = BulletinResource::collection($bulletins);

        return $bulletins;
    }
}