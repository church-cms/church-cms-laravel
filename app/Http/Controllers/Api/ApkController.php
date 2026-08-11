<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * ApkController
 *
 * Manages APK file download and mobile app distribution.
 * Provides version information and download endpoints for mobile application.
 *
 * @package App\Http\Controllers\Api
 */
class ApkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

         $array=[
                "versionid" => "1.0.2",
                "versioncode"=> 7,
                "appname"=> "ChurchCMS"
                ];

        return $array;

    }

}
