<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\Feedback as FeedbackResource;
use App\Http\Requests\Api\Guest\FeedbackRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\FeedbackMessage;
use App\Helpers\SiteHelper;
use App\Models\Feedback;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;

class FeedbackController extends Controller
{
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $categoryList = SiteHelper::getFeedbackCategoryList();

        return response()->json([
            'status'            => 'success',
            'data'              => $categoryList,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = User::where('id', Auth::id())->first();
        $feedback = Feedback::where('user_id', $user->id)->get();

        $feedback = FeedbackResource::collection($feedback);

        return $feedback;
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
        try {

            $admin = User::where('church_id', $request->church_id)->ByRole(3)->first();
            $user = User::where([['church_id', $request->church_id], ['mobile_no', $request->mobile_no]])->first();

            $feedback = new Feedback;

            $feedback->church_id = $request->church_id;
            if ($user) {
                $feedback->user_id  = $user->id;
            } else {
                $feedback->mobile_no = $request->mobile_no;
            }
            $feedback->admin_id = $admin->id;

            if ($feedback->save()) {
                $feedbackMessage = new FeedbackMessage;

                $feedbackMessage->message       = $request->message;
                $feedbackMessage->user_id       = $user->id;
                $feedbackMessage->church_id     = $request->church_id;
                $feedbackMessage->feedback_id   = $feedback->id;
                $feedbackMessage->category      = $request->category;

                /*$i =0;
                $files = $request->file('files');
                if(count($files) > 0)
                {
                    $path = [];
                    foreach($files as $file)
                    {
                        $path[$i] = $this->uploadFile($request->church_id.'/feedbacks/'.$feedback->id,$file);
                        $i++;
                    }
                    $feedbackMessage->file = $path;
                }*/

                if ($feedbackMessage->save()) {
                    $success = true;
                    $res = 'Feedback Sent Successfully';
                } else {
                    $success = false;
                    $res = 'Failed To Send Feedback';
                }
            } else {
                $success = false;
                $res = 'Failed To Send Feedback';
            }
            return response()->json([
                'success' => $success,
                'message' => $res,
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
