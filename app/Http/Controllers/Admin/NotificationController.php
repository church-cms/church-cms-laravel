<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Notification\NotificationResource;
use App\Notifications\NewMessageNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Notification;
use Exception;
use Log;

/**
 * NotificationController
 *
 * Manages system and user notifications within the admin panel.
 * Handles notification retrieval, marking as read, and notification management.
 * Provides real-time notification updates and notification preferences for administrators.
 *
 * @package App\Http\Controllers\Admin
 * @uses NewMessageNotification for message notification events
 * @uses NotificationResource for API resource transformation
 */
class NotificationController extends Controller
{
    //

    /**
     * Fetches the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexList()
    {
        try
        {
            $array = [];

            $unreadNotifications = \DB::table('notifications')->where('notifiable_id',Auth::id())->whereNull('read_at')->get();

            $unreadNotifications = NotificationResource::collection($unreadNotifications);

            $readNotifications = \DB::table('notifications')->where('notifiable_id',Auth::id())->whereNotNull('read_at')->orderBy('read_at','ASC')->get();

            $readNotifications = NotificationResource::collection($readNotifications);

            $array['read_list']     = $readNotifications;
            $array['unread_list']   = $unreadNotifications;

            return $array;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }

    public function index()
    {
    	//
    	return view('/admin/notification/index');
    }

    /**
     * Mark the notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            if(Auth::user())
            {
                if($request->notification_id!='all')
                {
                	\DB::table('notifications')->where('id', $request->notification_id)->where('notifiable_id',Auth::id())->whereNull('read_at')->update(['read_at' => Carbon::now()]);

      				$res['success'] = "Notification Read Successfully";
        			return $res;
                }
                else
                {
                    \DB::table('notifications')->where('notifiable_id',Auth::id())->whereNull('read_at')->update(['read_at' => Carbon::now()]);

                    $res['success'] = "All Notifications Read Successfully";
                    return $res;
                }
        	}
        }
      	catch(Exception $e)
      	{
            Log::info($e->getMessage());

      	}
    }

    /**
     * Fetches the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        try
        {
        	$array=[];
            if(Auth::user())
            {
              	$array['count']=count(Auth::user()->unreadNotifications);
              	$notifications= Auth::user()->unreadNotifications->take(5);
              	$i=0;
             	foreach ($notifications as $notification)
                {

                        $val = '';
              $type = null;
        // Ensure data is array
                   $data = is_array($notification->data)
            ? $notification->data
            : json_decode($notification->data, true);

        // Check valid array
        if (is_array($data) && isset($data['data'])) {

            // If nested array exists
            if (is_array($data['data']) && count($data['data']) > 1) {
                $val  = $data['data']['data'] ?? '';
                $type = $data['data']['type'] ?? null;
            } else {
                $val  = $data['data'];
                $type = null;
            }
          }
                    $array['list'][$i]['notification_id']=$notification['id'];
                    $array['list'][$i]['data']=$val;
                    $array['list'][$i]['type']=$type;
                    $array['list'][$i]['date']=$notification->created_at->diffForHumans();
                    $i++;
                }
            }
        	return $array;
        }
      	catch(Exception $e)
      	{
        	Log::info($e->getMessage());

      	}
    }
}
