<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GuestAddRequest;
use App\Events\VerificationMailEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\RegisterUser;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Models\Userprofile;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Cache;

/**
 * GuestAddController
 *
 * Handles guest/visitor registration in the church system.
 * Manages creation of new guest accounts with verification and subscription checks.
 * Validates guest data and integrates with user registration process.
 *
 * @package App\Http\Controllers\Admin
 * @uses RegisterUser Trait for user registration logic
 * @uses LogActivity Trait for activity tracking
 * @uses Common Trait for utility functions
 */
class GuestAddController extends Controller
{
    //
    use RegisterUser;
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $ref_name = request('ref_name')?request('ref_name'):'';
        $count    = User::ByRole(5)->ByChurch(Auth::user()->church_id)->count();

        return view('/admin/guest/create',['ref_name' => $ref_name , 'count' => $count , 'subscription' => $subscription]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function member()
    {
        $array = [];

        $array['countrylist']       =   SiteHelper::getCountries();
        $array['statelist']         =   SiteHelper::getStates();
        $array['citylist']          =   SiteHelper::getCities();
        $array['occupationlist']    =   SiteHelper::getOccupationList();
        $array['maritalstatuslist'] =   SiteHelper::getMaritalStatusList();
        $array['relationlist']      =   SiteHelper::getRelationList();

        return response()->json($array);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validationUser(GuestAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuestAddRequest $request)
    {
        //
        try
        {
            $church_id = Auth::user()->church_id;

            $file = $request->file('avatar');
            if($file)
            {
                $folder = $church_id.'/guest/avatar';
                $path   = $this->uploadFile($folder,$file);
            }
            else
            {
                $path = '';
            }
            $user = $this->createGuest($request , $church_id , $path , 5);


            $guest_count = 'guestCount'.$church_id;
            $maleGuestCount = 'maleGuestCount'.$church_id;
            $femaleGuestCount = 'femaleGuestCount'.$church_id;
            $recentMember = 'recentMember'.$church_id;
            Cache::forget($guest_count);
            Cache::forget($maleGuestCount);
            Cache::forget($femaleGuestCount);
            Cache::forget($recentMember);

            if( (env('MAIL_STATUS') === "on") && ($user->email != '') )
            {
                event(new VerificationMailEvent($user));
            }

            $message = 'Guest Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_GUEST,
                $message
            );

            return redirect()->back()->with('successmessage',$message);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
