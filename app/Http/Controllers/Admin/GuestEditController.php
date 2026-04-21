<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GuestUpdateRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Models\Country;
use App\Traits\Common;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Exception;
use Log;

/**
 * GuestEditController
 *
 * Handles guest/visitor account editing and updates.
 * Manages guest profile updates including contact and demographic information.
 * Supports country/state/city selection for guest location data.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class GuestEditController extends Controller
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
        $userprofile = Userprofile::where('id',$user->id)->first();

        $array = [];

        $array['firstname']             =   $userprofile->firstname;
        $array['lastname']              =   $userprofile->lastname;
        $array['aadhar_number']         =   $userprofile->aadhar_number === null ? null:$userprofile->aadhar_number;
        $array['date_of_birth']         =   $userprofile->date_of_birth === null ? null:date('Y-m-d',strtotime($userprofile->date_of_birth));
        $array['gender']                =   $userprofile->gender;
        $array['profession']            =   $userprofile->profession;
        $array['sub_occupation']        =   $userprofile->sub_occupation;
        $array['country_id']            =   $userprofile->country_id;
        $array['state_id']              =   $userprofile->state_id;
        $array['city_id']               =   $userprofile->city_id;
        $array['pincode']               =   $userprofile->pincode;
        $array['notes']                 =   $userprofile->notes;
        $array['was_baptized']          =   $userprofile->was_baptized;
        $array['baptism_date']          =   $userprofile->baptism_date === null ? null:date('Y-m-d',strtotime($userprofile->baptism_date));

        $array['countrylist']       	=   SiteHelper::getCountries();
        $array['statelist']         	=   SiteHelper::getStates();
        $array['citylist']          	=   SiteHelper::getCities();
        $array['occupationlist']    	=   SiteHelper::getOccupationList();

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
            return view('/admin/guest/edit',['user' => $user ]);
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
    public function validationGuestEdit(GuestUpdateRequest $request,$name)
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
    public function update(GuestUpdateRequest $request,$name)
    {
        //
        try
        {
            $user = User::where('name',$name)->first();
            $userprofile = Userprofile::where('user_id',$user->id)->first();

            $userprofile->firstname             = $request->firstname;
            $userprofile->lastname              = $request->lastname;
            $userprofile->gender                = $request->gender;
            $userprofile->date_of_birth         = $request->date_of_birth;

            $userprofile->address               = $request->address;
            $userprofile->city_id               = $request->city_id;
            $userprofile->state_id              = $request->state_id;
            $userprofile->country_id            = $request->country_id;
            $userprofile->pincode               = $request->pincode;
            $userprofile->profession            = $request->profession;
            $userprofile->sub_occupation        = $request->sub_occupation;
            $userprofile->aadhar_number         = $request->aadhar_number;
            $userprofile->notes                 = $request->notes;

            $userprofile->save();

            $message=('Guest Details Updated Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_GUEST,
                $message
                );
            \Session::put('successmessage','Guest Details Updated Successfully');
            return redirect()->back();
        }

        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
