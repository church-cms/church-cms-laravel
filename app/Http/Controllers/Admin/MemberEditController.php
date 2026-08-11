<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserProfileUpdateRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

/**
 * MemberEditController
 *
 * Handles member profile editing and updates.
 * Manages member profile updates including contact and demographic information.
 * Supports country/state/city selection for member location data.
 * Integrates with permission checks for edit access control.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class MemberEditController extends Controller
{
    //
    use LogActivity;
    use Common;

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

        $array['ref_id']                =   $user->ref_id;
        $array['firstname']             =   $userprofile->firstname;
        $array['lastname']              =   $userprofile->lastname;
        $array['birth_firstname']       =   $userprofile->birth_firstname;
        $array['birth_lastname']        =   $userprofile->birth_lastname;
        $array['aadhar_number']         =   $userprofile->aadhar_number;
        $array['date_of_birth']         =   $userprofile->date_of_birth === null ? null:date('Y-m-d',strtotime($userprofile->date_of_birth));
        $array['gender']                =   $userprofile->gender;
        $array['profession']            =   $userprofile->profession;
        $array['sub_occupation']        =   $userprofile->sub_occupation ===  null ? null:$userprofile->sub_occupation;
        $array['country_id']            =   $userprofile->country_id;
        $array['state_id']              =   $userprofile->state_id;
        $array['city_id']               =   $userprofile->city_id;
        $array['pincode']               =   $userprofile->pincode;
        $array['family']                =   $userprofile->family;
        $array['marriage_status']       =   $userprofile->marriage_status;
        $array['marriage_start_date']	=   $userprofile->marriage_start_date === null ? null:date('Y-m-d',strtotime($userprofile->marriage_start_date));
        $array['marriage_end_date']     =   $userprofile->marriage_end_date === null ? null:date('Y-m-d',strtotime($userprofile->marriage_end_date));
        $array['avatar_display']        =   $userprofile->AvatarPath;
        $array['membership_type']       =   $userprofile->membership_type;
        $array['relation']              =   $userprofile->relation;
        $array['notes']                 =   $userprofile->notes;
        $array['was_baptized']          =   $userprofile->was_baptized;
        $array['baptism_date']          =   $userprofile->baptism_date === null ? null:date('Y-m-d',strtotime($userprofile->baptism_date));

        $array['countrylist']       	=   SiteHelper::getCountries();
        $array['statelist']         	=   SiteHelper::getStates();
        $array['citylist']          	=   SiteHelper::getCities();
        $array['occupationlist']    	=   SiteHelper::getOccupationList();
        $array['maritalstatuslist'] 	=   SiteHelper::getMaritalStatusList();
        $array['relationlist']      	=   SiteHelper::getRelationList();

        return response()->json($array);
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
            return view('/admin/member/edit',['user' => $user ]);
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
    public function update(UserProfileUpdateRequest $request,$name)
    {
        //
        try
        {
            $user = User::where('name',$name)->first();
            $userprofile = Userprofile::where('user_id',$user->id)->first();

            if(request('avatar'))
            {
              $file = $request->file('avatar');
              $folder=Auth::user()->church_id.'/member/avatar';
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
            $userprofile->gender                = $request->gender;
            $userprofile->date_of_birth         = $request->date_of_birth;

            $userprofile->profession            = $request->profession;
            $userprofile->sub_occupation        = $request->sub_occupation;
            $userprofile->address               = $request->address;
            $userprofile->city_id               = $request->city_id;
            $userprofile->state_id              = $request->state_id;
            $userprofile->country_id            = $request->country_id;
            $userprofile->pincode               = $request->pincode;
           /* if($request->membership_type != null)
            {
                $userprofile->membership_type       = $request->membership_type;
            }*/
            $userprofile->membership_type       = "member";

            $userprofile->family                = $request->family;
            $userprofile->marriage_status       = $request->marriage_status;
            $userprofile->marriage_start_date   = $request->marriage_start_date;

            $userprofile->relation              = $request->relation;
            $userprofile->aadhar_number         = $request->aadhar_number;
            $userprofile->notes                 = $request->notes;

            $userprofile->save();

            $message=('Member Details Updated Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_MEMBER,
                $message
                );
            \Session::put('successmessage','Member Details Updated Successfully');
            return redirect()->back();
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
