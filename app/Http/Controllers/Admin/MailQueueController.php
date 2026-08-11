<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\MailQueue as MailQueueResource;
use App\Http\Requests\MailQueueRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Traits\LogActivity;
use App\Models\MailQueue;
use App\Traits\Common;
use Exception;
use Log;

/**
 * MailQueueController
 *
 * Manages the email queue for asynchronous email delivery.
 * Handles email queue monitoring, status tracking, and manual queue management.
 * Tracks email delivery status and handles failed email reprocessing.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for recording queue operations
 * @uses Common Trait for helper functions
 */
class MailQueueController extends Controller
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
        return view('/admin/mailqueue/index');
    }

    public function list()
    {
        $mailqueue = MailQueue::ByChurch(Auth::user()->church_id)->orderby('id','desc')->get();

        $mailqueue= MailQueueResource::collection($mailqueue);

        return $mailqueue;
    }

    public function edit( $id )
    {
        $mailqueue = MailQueue::where('id', $id)->first();

        return view('/admin/mailqueue/edit',['mailqueue' => $mailqueue]);
    }

    public function update(MailQueueRequest $request, $id)
    {
        try {

            $mailqueue = MailQueue::where( 'id', $id )->first();

            $mailqueue->fired_at  = $request->fired_at;
            $mailqueue->save();

            $message = 'Mail Queue Updated Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $mailqueue,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_MAILQUEUE,
                $message
            );

            $res['success'] = $message;
            return $res;
        }
        catch( Exception $e )
        {
            Log::info($e->getMessage());
        }
    }

    public function show($id)
    {
        $mailqueue = MailQueue::where('id', $id)->first();

        return view('/admin/mailqueue/show',['mailqueue' => $mailqueue]);
    }

    public function destroy($id)
    {
        //
        try
        {
            $mailqueue = MailQueue::where('id', $id)->first();

            $mailqueue->delete();

            $message = 'Mail Queue Deleted Successfully';

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $mailqueue,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_MAILQUEUE,
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
