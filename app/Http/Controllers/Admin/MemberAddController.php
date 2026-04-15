<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserProfileAddRequest;
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
use Log;
use Cache;

/**
 * MemberAddController
 *
 * Handles member/congregation registration in the church system.
 * Manages creation of new member accounts with verification and subscription checks.
 * Validates member data and integrates with user registration process.
 * Supports family relationships and member categorization.
 *
 * @package App\Http\Controllers\Admin
 * @uses RegisterUser Trait for user registration logic
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class MemberAddController extends Controller
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
        
        $membership_start_date = Carbon::now()->format('Y-m-d');


        return view('/admin/member/create',['ref_name' => $ref_name , 'membership_start_date' => $membership_start_date  ,'count' => $count ]);
        //  'membership_end_date'=>$membership_end_date
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
    public function validationUser(UserProfileAddRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProfileAddRequest $request)
    {
        //
        try
        {
            $church_id = Auth::user()->church_id;

            $file = $request->file('avatar');
            if($file)
            {
                $folder = $church_id.'/member/avatar';
                $path   = $this->uploadFile($folder,$file);
            }
            else
            {
                $path = '';
            }

            $request->request->set('membership_type', "member");

            $user = $this->CreateUser($request , $church_id , $path , 5);


            $member = 'memberCount'.$church_id;
            $male_member = 'maleMemberCount'.$church_id;
            $female_member = 'femaleMemberCount'.$church_id;
            $recentMember = 'recentMember'.$church_id;
            Cache::forget($member);
            Cache::forget($male_member);
            Cache::forget($female_member);
            Cache::forget($recentMember);

            if( (env('MAIL_STATUS') === "on") && ($user->email != '') )
            {
                event(new VerificationMailEvent($user));
            }

            $message = 'Member Added Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $user,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_MEMBER,
                $message
            );

            return redirect()->back()->with('successmessage','Member Added Successfully');
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
