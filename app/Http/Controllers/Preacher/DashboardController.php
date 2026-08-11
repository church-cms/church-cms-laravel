<?php

namespace App\Http\Controllers\Preacher;

use App\Http\Controllers\Controller;

/**
 * DashboardController
 *
 * Displays preacher dashboard and analytics.
 * Provides overview of preacher-related activities and statistics.
 * Main entry point for preacher area of the application.
 *
 * @package App\Http\Controllers\Preacher
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/preacher/dashboard');
    }
}
