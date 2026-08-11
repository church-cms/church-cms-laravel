<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function terms()
    {
        return view('/pages/terms');
    }
}
