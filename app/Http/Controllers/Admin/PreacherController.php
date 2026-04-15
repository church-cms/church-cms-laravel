<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\UserDetail as UserDetailResource;
use App\Http\Requests\PreacherUpdateRequest;
use App\Http\Requests\PreacherAddRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\MemberProcess;
use App\Traits\RegisterUser;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Models\Country;
use App\Models\Sermon;
use App\Traits\Common;
use App\Models\State;
use App\Models\User;
use Exception;
use Log;

/**
 * PreacherController
 *
 * Manages preacher/minister account registration and management.
 * Handles preacher account creation, updates, and permission assignments.
 * Integrates with sermon management and role-based access control.
 *
 * @package App\Http\Controllers\Admin
 * @uses MemberProcess Trait for member processing
 * @uses RegisterUser Trait for user registration
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class PreacherController extends Controller
{
    use MemberProcess;
    use RegisterUser;
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        //
        return $this->PreacherFilter($request,Auth::user()->church_id,6);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count    = User::ByRole(6)->ByChurch(Auth::user()->church_id)->count();
        $alphabet = request('alphabet')?request('alphabet'):'';
        $query    = \Request::getQueryString();

        return view('/admin/preacher/index',['alphabet' => $alphabet , 'query' => $query , 'count' => $count]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $count    = User::ByRole(6)->ByChurch(Auth::user()->church_id)->count();
       
        return view('/admin/preacher/create',['count'=>$count]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PreacherAddRequest $request)
    {
        //
        try
        {
            $church_id = Auth::user()->church_id;

            $file = $request->file('avatar');
            if($file)
            {
                $path = $this->uploadFile('uploads/admin/preacher/avatar',$file);
            }
            else
            {
                $path = '';
            }

            $user = $this->CreateUser($request , $church_id , $path , 6);
            $user->attachPermissions(['create-sermons','read-sermons','update-sermons']);
            $message = 'Preacher Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_PREACHER,
                $message
            );

            $res['success'] ="Preacher Added Successfully";
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetails($name)
    {
        //
        $users = User::with('userprofile')->where('name', $name)->get();
        $users = UserDetailResource::collection($users);

        return $users;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        //
        $user = User::with('userprofile')->where('name', $name)->first();

        $sermon = Sermon::where('user_id',$user->id)->get();

        $query   = \Request::getQueryString();

        if(Gate::allows('preacher',$user))
        {
            return view('/admin/preacher/show',['user' => $user , 'query' => $query , 'sermon' => $sermon]);
        }
        else
        {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList($name)
    {
        //
        $user = User::where('name',$name)->first();
        $userprofile = Userprofile::where('user_id',$user->id)->first();

        $array = [];

        $array['firstname']         = $userprofile->firstname;
        $array['lastname']          = $userprofile->lastname;
        $array['avatar_display']    = $userprofile->AvatarPath;
        $array['description']       = $userprofile->description === null ? null:$userprofile->description;
        $array['facebook_id']       = $userprofile->facebook_id === null ? null:$userprofile->facebook_id;

        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        //
        $user = User::where('name',$name)->first();
        $userprofile = Userprofile::where('user_id',$user->id)->first();
        if(Gate::allows('preacher',$userprofile))
        {
          return view('/admin/preacher/edit',['user' => $user]);
        }
        else
        {
          abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PreacherUpdateRequest $request,$name)
    {
        //
        try
        {
            $user = User::where('name',$name)->first();
            $userprofile = Userprofile::where('user_id',$user->id)->first();

            if(request('avatar'))
            {
                $file = $request->file('avatar');
                $path = $this->uploadFile('uploads/admin/preacher/avatar',$file,'public');
                $userprofile->avatar = $path;
            }
            else
            {
                $userprofile->avatar = $userprofile->avatar;
            }

            $userprofile->firstname             = $request->firstname;
            $userprofile->lastname              = $request->lastname;
            $userprofile->description           = $request->description;
            $userprofile->facebook_id           = $request->facebook_id;

            $userprofile->save();

            $message= 'Preacher Details Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_PREACHER,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
