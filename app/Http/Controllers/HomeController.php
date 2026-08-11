<?php

namespace App\Http\Controllers;

/**
 * HomeController
 *
 * Displays the authenticated user's application dashboard.
 * Entry point for authenticated users after login, showing personalized dashboard content.
 *
 * @package App\Http\Controllers
 * @middleware auth
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
