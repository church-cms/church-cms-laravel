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
use OpenApi\Attributes as OA;

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
    #[OA\Get(
        path: '/api/v1/feedbacks',
        tags: ['Feedback'],
        summary: 'Get all feedbacks submitted by the authenticated user',
        operationId: 'a15a31f8309a98a18a63a0cab53a13ae',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/FeedbackResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function index()
    {
        //
        $user = User::where('id', Auth::id())->first();
        $feedback = Feedback::where('user_id', $user->id)->get();

        $feedback = FeedbackResource::collection($feedback);

        return $feedback;
    }

    #[OA\Get(
        path: '/api/v1/feedback/category/list',
        tags: ['Feedback'],
        summary: 'Get feedback category list',
        operationId: 'ac6f79957960ab699e0247577733376c',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/FeedbackCategoryResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
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
    #[OA\Post(
        path: '/api/v1/feedback/add',
        tags: ['Feedback'],
        summary: 'Submit a new feedback message',
        operationId: '6973684c2bac7753c462ffb7ac5169fa',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/AddFeedbackRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/AddFeedbackResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function store(FeedbackRequest $request)
    {
        //
        try {
            $user = User::where('id', Auth::id())->first();
            $admin = User::where('church_id', $user->church_id)->ByRole(3)->first();

            $feedback = new Feedback;

            $feedback->church_id = Auth::user()->church_id;
            $feedback->user_id = $user->id;
            $feedback->admin_id = $admin->id;

            if ($feedback->save()) {
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

                if ($feedbackMessage->save()) {
                    $res['message'] = 'Message Sent Successfully';

                    $array = [];
                    $admin = SiteHelper::getAdmin(Auth::user()->church_id);
                    $array['user']     = $admin;
                    $array['details']  = 'New Feedback Received';

                    event(new SingleNotificationEvent($array));
                } else {
                    $res['message'] = 'Failed To Send Message';
                }
            } else {
                $res['message'] = 'Failed To Send Message';
            }
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
