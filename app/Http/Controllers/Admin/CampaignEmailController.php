<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Showcampaign as ShowcampaignResource;
use App\Http\Resources\Email as EmailResource;
use App\Http\Requests\CampaignEmailRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Email;
use Exception;
use Log;

/**
 * CampaignEmailController
 *
 * Handles individual campaign email configurations and templates.
 * Manages email content, subject lines, and sender settings for campaigns.
 * Supports email testing and preview functionality.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for email activity tracking
 * @uses Common Trait for utility functions
 */
class CampaignEmailController extends Controller
{

    use LogActivity;
    use Common;

    public function create($id)
    {
        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/campaign/show/'.$id);
        }

        return view('/admin/campaignemail/create',['campaign_id' => $id , 'prev_url' => $prev_url]);
    }

    public function store(CampaignEmailRequest $request,$id)
    {
        try
        {
            $campaignemail = new CampaignEmail;

            $campaignemail->church_id       = Auth::user()->church_id;
            $campaignemail->campaign_id     = $id;
            $campaignemail->email_id        = $request->email_id;
            $campaignemail->delay_in_days   = $request->delay_in_days;
            $campaignemail->delay_in_hours  = $request->delay_in_hours;

            $campaignemail->save();

            $message = 'Campaign Email Created Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $campaignemail,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_CAMPAIGN_EMAIL,
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

    public function editList($id)
    {
        $campaignemail = CampaignEmail::where('id',$id)->first();
        $list = Email::where('church_id',Auth::user()->church_id)->get();

        $array = [];

        $array['emailList']         = EmailResource::collection($list);
        $array['email_id']          = $campaignemail->email_id;
        $array['delay_in_days']     = $campaignemail->delay_in_days;
        $array['delay_in_hours']    = $campaignemail->delay_in_hours;

        return $array;
    }

    public function edit($id)
    {
        $campaignemail = CampaignEmail::where('id',$id)->first();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/campaign/show/'.$campaignemail->campaign_id);
        }

        return view('/admin/campaignemail/edit',['campaignemail' => $campaignemail , 'prev_url' => $prev_url]);
    }

    public function update(CampaignEmailRequest $request,$id)
    {
        try
        {
        	$campaignemail = CampaignEmail::where('id',$id)->first();

            $campaignemail->email_id        = $request->email_id;
            $campaignemail->delay_in_days   = $request->delay_in_days;
            $campaignemail->delay_in_hours  = $request->delay_in_hours;

        	$campaignemail->save();

            $message = 'Campaign Email Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $campaignemail,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_CAMPAIGN_EMAIL,
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

    public function show($id)
    {
        $campaignemail = CampaignEmail::where([['id',$id],['church_id',Auth::user()->church_id]])->first();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/campaign/show/'.$campaignemail->campaign_id);
        }

        return view('/admin/campaignemail/show',['campaignemail' => $campaignemail , 'prev_url' => $prev_url]);
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
        try
        {
            $campaignemail = CampaignEmail::where('id',$id)->first();

            $campaignemail->delete();

            $message = 'Campaign Email Deleted Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $campaignemail,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_CAMPAIGN_EMAIL,
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
