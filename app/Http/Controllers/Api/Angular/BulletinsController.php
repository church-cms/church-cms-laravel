<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\API\Bulletin as BulletinResource;
use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\Church;

class BulletinsController extends Controller
{
    public function showBulletins($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();

        $bulletins = Bulletin::where('church_id', $church->id)->get();

        $bulletins = BulletinResource::collection($bulletins);

        return $bulletins;
    }
}
