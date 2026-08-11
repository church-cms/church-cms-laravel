<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\Church as ChurchResource;
use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\City;

/**
 * ChurchController
 *
 * Provides church information and location-based church listings via API.
 * Delivers church details grouped by city location for discovery.
 *
 * @package App\Http\Controllers\Api
 */
class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function locationList()
    {
        //dd("jJ");
        //
        $churches = Church::where('status',1)->get()->groupBy('city_id');


        $array = [];
        $cities = City::where('country_id',100)->where('status',1)->take(10)->get();
        $i = 0;

        //dd($cities);
        foreach ($cities as $city)
        {
            $church = Church::where('city_id',$city->id)->count();

            $array['data'][$i]['city']          = $city->name;
            $array['data'][$i]['city_id']       = $city->id;
            $array['data'][$i]['church_count']  = $church;

            $i++;
        }

        return $array;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function churchList($city_id)
    {
        //
        $churches = Church::where([['city_id',$city_id],['status',1]])->get();

        $churches = ChurchResource::collection($churches);

        return $churches;
    }
}
