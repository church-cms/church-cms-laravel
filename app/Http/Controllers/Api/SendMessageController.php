<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Notification\NotificationResource;
use App\Http\Resources\API\SendMail as SendMailResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SiteHelper;
use App\Models\SendMail;
use App\Traits\Common;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Log;
use OpenApi\Attributes as OA;

class SendMessageController extends Controller
{
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    #[OA\Get(
        path: '/api/v1/messages',
        tags: ['Messages'],
        summary: 'List notification messages for the current user',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/MessageListResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function index()
    {
        //
        $messages =  SendMail::where([['church_id', Auth::user()->church_id], ['mode', 'notification'], ['user_id', Auth::id()]])->orderBy('fired_at', 'desc')->get();

        $messages = SendMailResource::collection($messages);

        return response()->json([
            'success'   =>  true,
            'message'   =>  'Messages List',
            'data'      =>  $messages
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    #[OA\Post(
        path: '/api/v1/message/read/{id}',
        tags: ['Messages'],
        summary: 'Mark a message as read',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Message ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/MessageReadResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function readMessage(Request $request, $id)
    {
        //
        try {
            $message =  SendMail::where([['id', $id], ['user_id', Auth::id()]])->first();

            $message->read_status = 1;
            $message->read_at = Carbon::now();

            $message->save();

            return response()->json([
                'success'   =>  true,
                //'message'   =>  'Messages List',
                //'data'      =>  $messages
            ], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    #[OA\Get(
        path: '/api/v1/notifications',
        tags: ['Notification'],
        summary: 'List push notifications for the current user',
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/NotificationListResponse'
            )
        ],
        security: [['sanctum' => []]]
    )]
    public function notificationList()
    {
        //   
        try {

            $user = User::where([['church_id', Auth::user()->church_id], ['id', Auth::id()]])->first();

            if ($user) {

                $notifications = \DB::table('notifications')->where('notifiable_id', Auth::id())->latest()->get();

                $notifications = NotificationResource::collection($notifications);

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  'Notification List',
                    'type'      =>  'notification',
                    'data'      =>  $notifications,
                ], 200);
            } else {
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'unauthorised',
                    'type'      =>  'notification',
                    'data'      =>  [],
                ], 401);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
