<?php

namespace App\Http\Controllers\Siteadmin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Models\Gallery;
use App\Models\Events;
use App\Traits\Common;
//use App\Models\Video;
use App\Models\File;
use App\Models\User;
use App\Models\Plan;
use Exception;

/**
 * SiteadminController
 *
 * Manages siteadmin profile and system administration features.
 * Handles siteadmin account updates and system configuration.
 * Provides tools for managing subscriptions, galleries, events and plans.
 *
 * @package App\Http\Controllers\Siteadmin
 */
class SiteadminController extends Controller
{

    use LogActivity;
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $subscription = Subscription::get();
        return view("/site_admin/index",['subscriptions'=>$subscription]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
      

        $membercount = User::where('church_id',$subscription->church_id)->ByRole('5')->count();
        $eventcount = Events::where('church_id',$subscription->church_id)->count();
        $gallerycount = Gallery::where('church_id',$subscription->church_id)->count();
        $filecount = File::where('church_id',$subscription->church_id)->count();



        return view("/site_admin/show",['membercount'=>$membercount , 'eventcount'=>$eventcount , 'gallerycount'=>$gallerycount , 'filecount'=>$filecount , 'membership'=>$membership]);//'videocount'=>$videocount ,
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubscription($id)
    {
        //
        $subscription = Subscription::where('id',$id)->first();

        return view("/site_admin/subscription_detail",['subscriptions'=>$subscription]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
        try
        {
            $subscription = Subscription::where('id',$id)->first();
            $userprofile = Userprofile::where('user_id', $subscription->user_id)->first();

            $userprofile->membership_type = $request->membership_type;

            $userprofile->save();

            $message=('User Membership Status Updated Successfully');
            $log_name='Changed_Membership_Status';
            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $userprofile,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                $log_name,
                $message
                );

            \Session::put('successmessage','User Membership Status Updated Successfully');
            return redirect()->back();
        }
        catch(Exception $e)
        {

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
