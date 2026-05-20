<?php

namespace App\Http\Controllers\Api;

use App\Events\Notification\SingleNotificationEvent;
use App\Http\Resources\API\Feedback as FeedbackResource;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\FeedbackMessage;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Models\Feedback;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

/**
 * FeedbackController
 *
 * Collects user feedback and support requests via API.
 * Handles feedback submission, tracking, and message threading.
 *
 * @package App\Http\Controllers\Api
 * @uses Common Trait for helper functions
 */
class FeedbackController extends Controller
{
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = User::where('id',Auth::id())->first();
        $feedback = Feedback::where('user_id',$user->id)->get();

        $feedback = FeedbackResource::collection($feedback);

        return $feedback;
    }

    public function list()
    {
        $categoryList = SiteHelper::getFeedbackCategoryList();

        return $categoryList;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedbackRequest $request)
    {
        //
        try
        {
            $user = User::where('id',Auth::id())->first();
            $admin = User::where('church_id',$user->church_id)->ByRole(3)->first();

            $feedback = new Feedback;

            $feedback->church_id = Auth::user()->church_id;
            $feedback->user_id = $user->id;
            $feedback->admin_id = $admin->id;

            if($feedback->save())
            {
                $feedbackMessage = new FeedbackMessage;

                $feedbackMessage->message       = $request->message;
                $feedbackMessage->user_id       = Auth::id();
                $feedbackMessage->church_id     = Auth::user()->church_id;
                $feedbackMessage->feedback_id   = $feedback->id;
                $feedbackMessage->category      = $request->category;

                // $i =0;
                // $files = $request->file('files');
                // if(count($files) > 0)
                // {
                //     $path = [];
                //     foreach($files as $file)
                //     {
                //         $path[$i] = $this->uploadFile(Auth::user()->church_id.'/feedbacks/'.$feedback->id,$file);
                //         $i++;
                //     }
                //     $feedbackMessage->file = $path;
                // }

                if($feedbackMessage->save())
                {
                    $res['message'] = 'Message Sent Successfully';

                    $array = [];
                    $admin = SiteHelper::getAdmin(Auth::user()->church_id);
                    $array['user']     =$admin ;
                    $array['details']  = 'New Feedback Received';

                    event(new SingleNotificationEvent($array));
                }
                else
                {
                    $res['message'] = 'Failed To Send Message';
                }
            }
            else
            {
                $res['message'] = 'Failed To Send Message';
            }
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
