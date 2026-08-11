<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\PrayerRequest as PrayerRequestResource;
use App\Http\Controllers\Controller;
use App\Models\Prayer;

class PrayerRequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($church_id)
    {
        $prayer = Prayer::where('church_id', $church_id)
                        ->where('status', Prayer::STATUS_ACTIVE)
                        ->get();
        $prayer = PrayerRequestResource::collection($prayer);

        return $prayer;
    }
}
