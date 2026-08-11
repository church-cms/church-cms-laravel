<?php

namespace App\Http\Controllers\Admin\EmailBlaster;

use App\Http\Resources\EmailBlaster\GetResponse as GetResponseResource;
use App\Http\Resources\Campaign as CampaignResource;
use App\Http\Requests\EmailBlaster\RuleAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\GetResponse;
use App\Traits\LogActivity;
use App\Models\Campaign;
use App\Traits\Common;
use Exception;
use Log;

/**
 * RulesController
 *
 * Manages email blaster GetResponse rules and automation.
 * Handles creation and management of email campaign rules.
 * Integrates with GetResponse API for email campaign automation.
 *
 * @package App\Http\Controllers\Admin\EmailBlaster
 */
class RulesController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $rules = GetResponse::where('church_id',Auth::user()->church_id)->get();

        $rules = GetResponseResource::collection($rules);

        return $rules;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/emailblaster/rules/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createList()
    {
        //
        $campaigns = Campaign::where('church_id',Auth::user()->church_id)->get();

        $campaigns = CampaignResource::collection($campaigns);

        return $campaigns;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/emailblaster/rules/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RuleAddRequest $request)
    {
        //
        try
        {
            $rule = new GetResponse;

            $rule->church_id                    = Auth::user()->church_id;
            $rule->campaign_id                  = $request->campaign_id;
            $rule->name                         = $request->name;
            $rule->email_open_campaign_id       = $request->email_open_campaign_id;
            $rule->no_email_open_campaign_id    = $request->no_email_open_campaign_id;
            $rule->day_after                    = $request->day_after;
            $rule->status                       = $request->status;

            $rule->save();

            $message = 'Rule Created Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $rule,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_RULE,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $rule = GetResponse::where('id',$id)->first();

        return view('/admin/emailblaster/rules/show',['rule' => $rule]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList($id)
    {
        //
        $campaigns = Campaign::where('church_id',Auth::user()->church_id)->get();

        $rule = GetResponse::where('id',$id)->first();

        $array = [];

        $array['campaignlist']              =   CampaignResource::collection($campaigns);
        $array['campaign_id']               =   $rule->campaign_id;
        $array['name']                      =   $rule->name;
        $array['email_open_campaign_id']    =   $rule->email_open_campaign_id;
        $array['no_email_open_campaign_id'] =   $rule->no_email_open_campaign_id;
        $array['day_after']                 =   $rule->day_after;
        $array['status']                    =   $rule->status;

        return $array;
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
        $rule = GetResponse::where('id',$id)->first();

        return view('/admin/emailblaster/rules/edit',['rule' => $rule]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RuleAddRequest $request, $id)
    {
        //
        try
        {
            $rule = GetResponse::where('id',$id)->first();

            $rule->campaign_id                  = $request->campaign_id;
            $rule->name                         = $request->name;
            $rule->email_open_campaign_id       = $request->email_open_campaign_id;
            $rule->no_email_open_campaign_id    = $request->no_email_open_campaign_id;
            $rule->day_after                    = $request->day_after;
            $rule->status                       = $request->status;

            $rule->save();

            $message = 'Rule Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $rule,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_RULE,
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
            $rule = GetResponse::where('id', $id)->first();

            $rule->delete();

            $message = 'Rule Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $rule,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_RULE,
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
