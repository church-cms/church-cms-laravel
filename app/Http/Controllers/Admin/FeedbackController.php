<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\SinglePushEvent;
use App\Models\FeedbackMessage;
use App\Traits\LogActivity;
use App\Models\Feedback;
use App\Traits\Common;
use App\Models\User;
use Exception;
use Log;

/**
 * FeedbackController
 *
 * Manages user feedback and support tickets.
 * Handles feedback submission, tracking, and response management.
 * Supports feedback message threads and issue resolution workflow.
 * Integrates with push notifications for feedback updates.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for recording feedback activities
 * @uses Common Trait for helper functions
 */
class FeedbackController extends Controller
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
        $feedbacks = Feedback::where('church_id',Auth::user()->church_id)->with(['user', 'admin','feedbackMessage'])->latest()->paginate(10);

        return view('admin/feedbacks/index', [ 'feedbacks' => $feedbacks ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($feedbackid)
    {
        //
        $messages = FeedbackMessage::where('feedback_id', $feedbackid)->with('feedback')->get();
        $feedback = Feedback::where('id', $feedbackid)->with(['user', 'admin','feedbackMessage'])->first();

        return view('admin/feedbacks/view', [ 'messages' => $messages , 'feedback' => $feedback ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request,$id)
    {
        //
        try
        {
            $feedbackMessage = FeedbackMessage::where('id', $id)->first();

            $feedbackMessage->is_seen = $request->status;

            $feedbackMessage->save();

            $feedback = Feedback::where('id',$feedbackMessage->feedback_id)->first();

            $message = 'Feedback Status Updated Successfully';

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $feedbackMessage,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_UPDATE_FEEDBACK_STATUS,
                $message
            );

            $data=[];

            $data['church_id']  =   Auth::user()->church_id;
            $data['user_id']    =   $feedback->user_id;
            $data['message']    =   'New Response For Your Feedback';
            $data['type']       =   'feedback';

            event(new SinglePushEvent($data));

            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
