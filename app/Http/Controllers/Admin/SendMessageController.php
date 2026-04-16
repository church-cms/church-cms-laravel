<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SendMailRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\SendMessageAllEvent;
use App\Traits\SendMessageProcess;
use App\Events\SendMessageEvent;
use App\Events\SinglePushEvent;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Traits\LogActivity;
use App\Traits\SmsProcess;
use App\Models\SendMail;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
/**
 * SendMessageController
 *
 * Handles sending messages and SMS communications to church members.
 * Supports bulk message sending with SMS and email integration.
 * Manages message scheduling and multi-recipient notification delivery.
 *
 * @package App\Http\Controllers\Admin
 * @uses SendMessageProcess Trait for message processing
 * @uses LogActivity Trait for audit logging
 * @uses SmsProcess Trait for SMS functionality
 * @uses Common Trait for helper functions
 */
class SendMessageController extends Controller
{
    use SendMessageProcess;
    use LogActivity;
    use SmsProcess;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


       $sub = SendMail::selectRaw('MAX(id) as id')
        ->where([
            ['church_id', Auth::user()->church_id],
            ['entity_id', null],
            ['entity_name', null]
        ])
        ->when($request->mode != '', function ($q) use ($request) {
            $q->where('mode', $request->mode);
        })
        ->groupBy('batch_id');

       $messages = SendMail::whereIn('id', $sub)
        ->orderBy('executed_at', 'desc')
        ->paginate(20);


        //dd($messages);

        return view('/admin/messages/index',['messages' => $messages]);
    }


    public function batchindex($batch_id)
    {


       $messages = SendMail::where([['batch_id',$batch_id]])->orderBy('executed_at','desc');

        if($request->mode!= '')
        {
            $messages = $messages->where('mode',$request->mode);
        }

        $messages = $messages->paginate(20);


        return view('/admin/messages/batch-index',['messages' => $messages]);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sent = SendMail::where('id',$id)->first();
        if(Gate::allows('member',$sent))
        {
            if($_SERVER['HTTP_REFERER'] != null)
            {
                $prev_url = $_SERVER['HTTP_REFERER'];
            }
            else
            {
                $prev_url = url('/admin/messages');
            }
            return view('/admin/messages/show',['sent' => $sent , 'prev_url' => $prev_url]);
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
    public function store(SendMailRequest $request,$name)
    {
        //

       // dd($request);
        try
        {
            $batch_id=(string) Str::uuid();
            $user = User::where('name',$name)->first();
            $this->sendMessage($request , Auth::user()->church_id , Auth::user()->email , $user , Auth::user(),$batch_id);

            $res['success'] = 'Message Sent Successfully';
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function memberstore(SendMailRequest $request)
    {
        //
        try
        {
            $data=[];
            $data['selected'] =explode(",",$request->selected);
            $data['membership_type']=$request->membership_type;
            $data['subject']=$request->subject;
            $data['message']=$request->message;
            $data['send_later']=$request->send_later;
            $data['executed_at']=$request->executed_at;
            $data['mode']=$request->mode;
            $data['attachments']=$request->attachments;
            $data['count']=$request->count;
            $datas=(object)$data;
            event (new SendMessageAllEvent ($datas , Auth::user()->church_id , Auth::user()->email , Auth::user() ) );

            $res['message'] = 'Message Sent Successfully';
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
