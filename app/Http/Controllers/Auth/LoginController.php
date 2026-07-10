<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Traits\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

/**
 * LoginController
 *
 * Handles user authentication and login functionality.
 * Uses AuthenticatesUsers trait for standardized authentication logic.
 * Redirects authenticated users to member home page after login.
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/admin/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (config('settings.member_web_login', 1) != 1) {
            return view('auth.login', ['blocked' => true]);
        }

        return view('auth.login', ['blocked' => false]);
    }

    protected function redirectTo()
    {
        $user = Auth::user();


        if ($user->usergroup_id == 3 || $user->usergroup_id == 4) {

            return '/admin/dashboard';
        }else{

        return '/member/home';

        }

        
    }
}
