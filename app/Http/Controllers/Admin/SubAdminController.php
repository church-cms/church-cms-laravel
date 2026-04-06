<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubAdminUpdateRequest;
use App\Http\Requests\SubAdminAddRequest;
use App\Events\VerificationMailEvent;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Traits\MemberProcess;
use App\Traits\RegisterUser;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * SubAdminController
 *
 * Manages sub-administrator account registration and management.
 * Handles sub-admin creation, updates, and role assignments.
 * Integrates with admin verification and role-based permissions.
 *
 * @package App\Http\Controllers\Admin
 * @uses MemberProcess Trait for member processing
 * @uses RegisterUser Trait for user registration
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class SubAdminController extends Controller
{
    //
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
        return $this->SubAdminFilter($request,Auth::user()->church_id,4);
    }

    public function filterList()
    {
        $array = [];

        $array['occupationlist']    = SiteHelper::getOccupationList();
        $array['genderlist']        = SiteHelper::getGenderList();
        $array['months']            = SiteHelper::getMonths();

        return $array;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      	$count    = User::ByRole(4)->ByChurch(Auth::user()->church_id)->count();
      	$alphabet = request('alphabet')?request('alphabet'):'';
      	$query    = \Request::getQueryString();
        if(request('date_of_birth') != null)
        {
            $type = 'date_of_birth';
        }
        if(request('marriage_status') != null)
        {
            $type = 'marriage_status';
        }
        if(request('location') != null)
        {
            $type = 'location';
        }

      	return view('/admin/subadmin/index',['alphabet' => $alphabet , 'query' => $query , 'count' => $count , 'type' => $type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $count    = User::ByRole(4)->ByChurch(Auth::user()->church_id)->count();
        $membership_start_date = Carbon::now()->format('Y-m-d');

        return view('/admin/subadmin/create',['membership_start_date' => $membership_start_date  , 'count' => $count]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationUser(SubAdminAddRequest $request)
    {
    	//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
      	try
      	{
        	$church_id = Auth::user()->church_id;

        	$file = $request->file('avatar');
        	if($file)
        	{
          		$folder=Auth::user()->church_id.'/member/avatar';
          		$path = $this->uploadFile($folder,$file);
        	}
        	else
        	{
          		$path = '';
        	}
        	$user = $this->CreateUser($request , $church_id , $path , 4);

        	if( (env('MAIL_STATUS') === "on") && ($user->email != '') )
        	{
            	event(new VerificationMailEvent($user));
        	}

        	$message=('Member Added Successfully');

        	$ip= $this->getRequestIP();
        	$this->doActivityLog(
          		$user,
          		Auth::user(),
          		['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
          		LOGNAME_ADD_SUBADMIN,
          		$message
        	);

        	return redirect()->back()->with('successmessage','Member Added Successfully');
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
    public function show($name)
    {
        //
      	$user = User::with('userprofile')->where('name', $name)->first();
      	if(Gate::allows('member',$user))
      	{
        	return view('/admin/subadmin/show',['user'=>$user]);
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
        if(Gate::allows('member',$user))
        {
            $userprofile = Userprofile::where('id',$user->id)->first();
        	$array = [];

        	$array['firstname'] 		= $userprofile->firstname;
        	$array['lastname'] 			= $userprofile->lastname;
        	$array['birth_firstname']	= $userprofile->birth_firstname;
        	$array['birth_lastname'] 	= $userprofile->birth_lastname;
            $array['aadhar_number']     = $userprofile->aadhar_number === null ? '':$userprofile->aadhar_number;
            $array['gender']            = $userprofile->gender;
            $array['date_of_birth']     = date('Y-m-d',strtotime($userprofile->date_of_birth));
            $array['profession']        = $userprofile->profession;
            $array['sub_occupation']    = $userprofile->sub_occupation;
            $array['address']           = $userprofile->address;
            $array['country_id']        = $userprofile->country_id;
            $array['state_id']          = $userprofile->state_id;
            $array['city_id']           = $userprofile->city_id;
            $array['pincode']           = $userprofile->pincode;
            $array['avatar']			= $userprofile->avatar === null ? null:$userprofile->AvatarPath;
            $array['notes']             = $userprofile->notes;

          	return $array;
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
    public function edit($name)
    {
        //
        $user = User::where('name',$name)->first();
        if(Gate::allows('member',$user))
        {
          	return view('/admin/subadmin/edit',['user' => $user]);
        }
        else
        {
          	abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editValidationUser(SubAdminUpdateRequest $request)
    {
    	//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$name)
    {
        //
        try
        {
            $user = User::where('name',$name)->first();
            $userprofile = Userprofile::where('id',$user->id)->first();

            if(request('avatar'))
            {
              	$file = $request->file('avatar');
              	$folder=Auth::user()->church_id.'/subadmin/avatar';
              	$path = $this->uploadFile($folder,$file);
              	$userprofile->avatar = $path;
            }
            else
            {
                $userprofile->avatar = $userprofile->avatar;
            }

            $userprofile->firstname             = $request->firstname;
            $userprofile->lastname              = $request->lastname;
            $userprofile->birth_firstname       = $request->birth_firstname;
            $userprofile->birth_lastname        = $request->birth_lastname;
            $userprofile->aadhar_number         = $request->aadhar_number;
            $userprofile->gender                = $request->gender;
            $userprofile->date_of_birth         = $request->date_of_birth;
            $userprofile->profession            = $request->profession;
            $userprofile->sub_occupation        = $request->sub_occupation;
            $userprofile->address               = $request->address;
            $userprofile->country_id            = $request->country_id;
            $userprofile->state_id              = $request->state_id;
            $userprofile->city_id               = $request->city_id;
            $userprofile->pincode               = $request->pincode;
            $userprofile->notes                 = $request->notes;

            $userprofile->save();

            $message= 'Sub Admin Details Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_SUBADMIN,
                $message
            );
            return redirect()->back()->with(['successmessage' => $message]);
        }

        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function getPermissions($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return response()->json([
            'all'      => Permission::orderBy('name')->get(['name']),
            'assigned' => $user->permissions()->get(['name']),
        ]);
    }

    public function updatePermissions(Request $request, $name)
    {
        $user = User::where('name', $name)->firstOrFail();
        $permissions = $request->input('permissions', []);
        $user->syncPermissions($permissions);
        return response()->json(['message' => 'Permissions updated successfully']);
    }
}
