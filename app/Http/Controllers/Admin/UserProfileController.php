<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\API\Country as CountryResource;
use App\Http\Resources\API\State as StateResource;
use App\Http\Resources\API\City as CityResource;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Models\Country;
use App\Traits\Common;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Exception;
use Hash;
use Log;

/**
 * UserProfileController
 *
 * Manages user profile information and personal details.
 * Handles profile updates, avatar management, password changes, and profile-specific operations.
 * Supports geographic location data (country, state, city selection).
 *
 * @package App\Http\Controllers\Admin
 * @uses Common Trait for helper functions
 * @uses LogActivity Trait for audit logging
 */
class UserProfileController extends Controller
{
    use Common;
    use LogActivity;

    public function getavatar()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();
        $array=[];

        if(Auth::user())
        {
            $array['avatar']    =   $userprofile->AvatarPath;
            $array['id']        =   $userprofile->id;
        }

        return $array;
    }

    public function changeavatar(Request $request)
    {
        return view('admin/changeavatar');
    }

    /**
     * Updates the avatar image for specified user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updatechangeavatar(Request $request)
    {
        try
        {
            $user = User::where('id', Auth::id())->first();
            $userprofile = Userprofile::where('user_id', Auth::id())->first();
            $path=$this->uploadFile('uploads/avatars', $request->avatar);
            if($path!='')
            {
                $userprofile->avatar = $path ;
                $userprofile->save();
                \Session::put('successmessage',__('admin_userprofile.update_avatar'));
                $res['message']=__('admin_userprofile.update_avatar');
                $res['avatar']=$this->getFilePath($path);
            }
            else
            {
                $userprofile->avatar ='uploads/user/avatar/default-user.jpg';
                $userprofile->save();
                \Session::put('failmessage',__('admin_userprofile.failed_avatar'));
                $res['message']=__('admin_userprofile.failed_avatar');
            }

            $ip= $this->getRequestIP();
                $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_CHANGE_AVATAR,
                'Changed Avatar'
            );
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function ChangePassword()
    {
        return view('/admin/changepassword');
    }

    /**
     * Updates the password of the specified user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        try
        {
            $user = User::find(Auth::id());
            $hashedPassword = $user->password;

            if($hashedPassword!='')
            {
                $user->password = Hash::make($request->newpassword);
                $user->save();

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $user,
                    Auth::user(),
                    ['ip' => $ip,'details' => $_SERVER['HTTP_USER_AGENT']],
                    LOGNAME_CHANGE_PASSWORD,
                    'Changed Profile Password.'
                );

                $successmessage = $request->session()->flash('successmessage',__('admin_userprofile.password_update'));
            }
            $res['message']=__('admin_userprofile.password_update');
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function edit(Request $request)
    {
        return view('/admin/editprofile');
    }

     public function create()
    {
        $array=[];

        $country = Country::where('status','1')->get();
        $country = CountryResource::collection($country);
        $state = State::get();
        $state = StateResource::collection($state)->groupby('country_id');
        $city = City::get();
        $city = CityResource::collection($city)->groupby('state_id');

        $userprofile = Userprofile::with('country','state','city')->where('user_id', Auth::id())->first();

        if($userprofile->firstname === null)
        {
            $array['firstname']='';
        }
        else
        {
            $array['firstname']=$userprofile->firstname;
        }

        if($userprofile->lastname === null)
        {
            $array['lastname']='';
        }
        else
        {
            $array['lastname']=$userprofile->lastname;
        }

        if($userprofile->birth_firstname === null)
        {
            $array['birth_firstname']='';
        }
        else
        {
            $array['birth_firstname']=$userprofile->birth_firstname;
        }

        if($userprofile->birth_lastname === null)
        {
            $array['birth_lastname']='';
        }
        else
        {
            $array['birth_lastname']=$userprofile->birth_lastname;
        }

        if($userprofile->gender === null)
        {
            $array['gender']='';
        }
        else
        {
            $array['gender']=$userprofile->gender;
        }

        if($userprofile->date_of_birth === null)
        {
            $array['date_of_birth']='';
        }
        else
        {
            $array['date_of_birth']=date('Y-m-d',strtotime($userprofile->date_of_birth));
        }

        if($userprofile->address === null)
        {
            $array['address']='';
        }
        else
        {
            $array['address']=$userprofile->address;
        }

        $array['country_id']    =   $userprofile->country_id;
        $array['state_id']      =   $userprofile->state_id;
        $array['city_id']       =   $userprofile->city_id;
        $array['pincode']       =   $userprofile->pincode;
        $array['countrylist']   =   $country;
        $array['statelist']     =   $state;
        $array['citylist']      =   $city;

        return $array;
    }

    public function update(Request $request)
    {
      try
        {
            $userprofile = Userprofile::where('user_id', Auth::id())->first();

            $userprofile->firstname         = $request->firstname;
            $userprofile->lastname          = $request->lastname;
            $userprofile->birth_firstname   = $request->birth_firstname;
            $userprofile->birth_lastname    = $request->birth_lastname;
            $userprofile->gender            = $request->gender;
            $userprofile->date_of_birth     = $request->date_of_birth;
            $userprofile->address           = $request->address;
            $userprofile->city_id           = $request->city_id;
            $userprofile->state_id          = $request->state_id;
            $userprofile->country_id        = $request->country_id;
            $userprofile->pincode           = $request->pincode;

            $userprofile->save();

            $ip= $this->getRequestIP();
            $this->doActivityLog
            (
                $userprofile,
                Auth::user(),
                ['ip' => $ip,'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_USERPROFILE,
                'Changed Profile'
            );

            $res['message']= "Userprofile updated successfully";
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
