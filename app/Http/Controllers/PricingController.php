<?php

namespace App\Http\Controllers;


/**
 * PricingController
 *
 * Displays subscription pricing and plan information to potential customers.
 * Shows available subscription plans and pricing tiers for the church CMS platform.
 *
 * @package App\Http\Controllers
 */
class PricingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $plan =array();
        return view('/pricing',['plans' => $plan]);
    }
}
