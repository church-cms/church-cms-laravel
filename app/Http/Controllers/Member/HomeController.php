<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Resources\UserDetail as UserDetailResource;
use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function changePassword()
    {
        return view('member.change-password');
    }

    public function showDetails($name)
    {
        $users = User::with('userprofile')->where('name', $name)->get();
        return UserDetailResource::collection($users);
    }

    public function familytree($name)
    {
        $tree = new AdminMemberController();
        return response()->json($tree->familytree($name));
    }
}

