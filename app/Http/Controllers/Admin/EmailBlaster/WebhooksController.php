<?php

namespace App\Http\Controllers\Admin\EmailBlaster;

use App\Http\Resources\EmailBlaster\Webhook as WebhookResource;
use App\Http\Resources\Mailinglist as MailinglistResource;
use App\Http\Requests\EmailBlaster\WebhookRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivity;
use App\Models\MailingList;
use App\Models\Webhook;
use App\Traits\Common;
use Exception;
use Log;

/**
 * WebhooksController
 *
 * Manages email blaster webhooks and external integration endpoints.
 * Handles webhook CRUD operations for email campaign integrations.
 * Processes external webhook events and configurations.
 *
 * @package App\Http\Controllers\Admin\EmailBlaster
 */
class WebhooksController extends Controller
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
        $webhooks = Webhook::ByChurch(Auth::user()->church_id)->get();

        $webhooks = WebhookResource::collection($webhooks);

        return $webhooks;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/admin/emailblaster/webhooks/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createList()
    {
        //
        $mailinglists = MailingList::where('church_id',Auth::user()->church_id)->get();

        $mailinglists = MailinglistResource::collection($mailinglists);

        return $mailinglists;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('/admin/emailblaster/webhooks/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WebhookRequest $request)
    {
        //
        try
        {
            $webhook = new Webhook;

            $webhook->mailinglist_id    = $request->mailinglist_id;
            $webhook->name              = $request->name;
            $webhook->handshake_key     = $request->handshake_key;
            $webhook->url               = $request->webhook_url;
            $webhook->status            = $request->status;

            $webhook->save();

            $message = 'Webhook Created Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $webhook,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_WEBHOOK,
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
        $webhook = Webhook::where('id',$id)->first();

        if($_SERVER['HTTP_REFERER'] != null)
        {
            $prev_url = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $prev_url = url('/admin/webhooks');
        }

        return view('/admin/emailblaster/webhooks/show',['webhook' => $webhook , 'prev_url' => $prev_url]);
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
        $mailinglists = MailingList::where('church_id',Auth::user()->church_id)->get();

        $webhook = Webhook::where('id',$id)->first();

        $array = [];

        $array['mailList']          =   MailinglistResource::collection($mailinglists);
        $array['mailinglist_id']    =   $webhook->mailinglist_id;
        $array['name']              =   $webhook->name;
        $array['handshake_key']     =   $webhook->handshake_key;
        $array['webhook_url']       =   $webhook->url;
        $array['status']            =   $webhook->status;

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
        $webhook = Webhook::where('id',$id)->first();

        return view('/admin/emailblaster/webhooks/edit',['webhook' => $webhook]);
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
            $webhook = Webhook::where('id',$id)->first();

            $webhook->mailinglist_id    = $request->mailinglist_id;
            $webhook->name              = $request->name;
            $webhook->handshake_key     = $request->handshake_key;
            $webhook->url               = $request->webhook_url;
            $webhook->status            = $request->status;

            $webhook->save();

            $message = 'Webhook Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $webhook,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_WEBHOOK,
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
            $webhook = Webhook::where('id', $id)->first();

            $webhook->delete();

            $message = 'Webhook Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $webhook,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_WEBHOOK,
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
