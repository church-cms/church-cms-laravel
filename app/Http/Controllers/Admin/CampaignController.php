<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Campaign as CampaignResource;
use App\Http\Requests\CampaignRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CampaignEmail;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\Campaign;
use App\Traits\Common;
use App\Models\Email;
use Exception;
use Log;

/**
 * CampaignController
 *
 * Manages email marketing campaigns for churches.
 * Handles campaign creation, configuration, association with mailing lists, and email sending.
 * Integrates with email templates and campaign email tracking via CampaignEmail model.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class CampaignController extends Controller
{
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/campaign/index');
    }

    public function list()
    {
        $campaigns = Campaign::where('church_id', Auth::user()->church_id)->get();

        $campaigns = CampaignResource::collection($campaigns);

        return $campaigns;
    }

    public function create()
    {
        return view('/admin/campaign/create');
    }

    public function store(CampaignRequest $request)
    {
        try
        {
            $campaign = new Campaign;

            $campaign->church_id    = Auth::user()->church_id;
            $campaign->name         = $request->name;
            $campaign->description  = $request->description;
            if($request->status === 'true')
            {
                $campaign->status = 1;
            }
            elseif($request->status === 'false')
            {
                $campaign->status = 0;
            }

            $campaign->save();

            $message = 'Campaign Created Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $campaign,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_CAMPAIGN,
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
        $campaign = Campaign::where('id',$id)->get();
        $campaign = CampaignResource::collection($campaign);

        return $campaign;
    }

    public function edit($id)
    {
        $campaign = Campaign::where('id', $id)->first();

        return view('/admin/campaign/edit', ['campaign' => $campaign]);
    }

    public function update(CampaignRequest $request, $id)
    {
        try
        {
            $campaign = Campaign::where('id', $id)->first();

            $campaign->name         = $request->name;
            $campaign->description  = $request->description;
            if($request->status === 'true')
            {
                $campaign->status = 1;
            }
            elseif($request->status === 'false')
            {
                $campaign->status = 0;
            }
            $campaign->save();

            $message = 'Campaign Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $campaign,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_CAMPAIGN,
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
        $campaign = Campaign::where([['id', $id],['church_id', Auth::user()->church_id]])->first();

        $campaignemails = CampaignEmail::where('campaign_id',$id)->get();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/campaigns');
        }

        return view('/admin/campaign/show',['campaign' => $campaign , 'campaignemails' => $campaignemails , 'prev_url' => $prev_url]);
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
            $campaign = Campaign::where('id', $id)->first();

            $campaignemails = CampaignEmail::where('campaign_id',$id)->get();
            foreach ($campaignemails as $campaignemail)
            {
                $campaignemail->delete();
            }

            $campaign->delete();

            $message = 'Campaign Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $campaign,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_CAMPAIGN,
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
