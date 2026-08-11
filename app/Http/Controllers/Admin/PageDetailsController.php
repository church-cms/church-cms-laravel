<?php

namespace App\Http\Controllers\Admin;

//use App\Events\Notification\SingleNotificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\PageDetail;
use App\Traits\Common;
use App\Models\User;
use App\Models\Page;
use Exception;
use Log;

/**
 * PageDetailsController
 *
 * Manages page follower relationships and page metadata.
 * Handles user follow/unfollow functionality for specific pages.
 * Tracks page interactions and user engagement with page content.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class PageDetailsController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request,$page_id)
    {
        //
        try
        {
            $page = Page::where('id',$page_id)->first();

            $page_detail = PageDetail::where([['user_id',Auth::id()],['page_id',$page_id]])->first();

            if($page_detail != null)
            {
                $page_detail->is_following = $request->is_following;

                $page_detail->save();
            }
            else
            {
                $page_detail = new PageDetail;

                $page_detail->user_id        = Auth::id();
                $page_detail->page_id        = $page_id;
                $page_detail->is_following   = $request->is_following;

                $page_detail->save();
            }

            $user = User::where('id',$page->created_by)->first();

            if($request->is_following === 1)
            {
                $message = trans('messages.follow_success_msg',['page' => $page->page_name]);


            }
            else
            {
                $message = trans('messages.unfollow_success_msg',['page' => $page->page_name]);


            }

            /*if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       =   $user;
                $data['details']    =   $details;

                event(new SingleNotificationEvent($data));
            }*/

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_FOLLOW_PAGE,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function like(Request $request,$page_id)
    {
        //
        try
        {
            $page = Page::where('id',$page_id)->first();
            $page_detail = PageDetail::where([['user_id',Auth::id()],['page_id',$page_id]])->first();

            if($page_detail != null)
            {
                $page_detail->like = $request->like;

                $page_detail->save();
            }
            else
            {
                $page_detail = new PageDetail;

                $page_detail->user_id    = Auth::id();
                $page_detail->page_id    = $page_id;
                $page_detail->like       = $request->like;

                $page_detail->save();
            }

            $user = User::where('id',$page->created_by)->first();
            if($request->like === 1)
            {
                $message = trans('messages.like_success_msg',['page' => $page->page_name]);


            }
            else
            {
                $message = trans('messages.remove_like_success_msg',['page' => $page->page_name]);


            }

            /*if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       =   $user;
                $data['details']    =   $details;

                event(new SingleNotificationEvent($data));
            }*/

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_LIKE_PAGE,
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dislike(Request $request,$page_id)
    {
        //
        try
        {
            $page = Page::where('id',$page_id)->first();
            $page_detail = PageDetail::where([['user_id',Auth::id()],['page_id',$page_id]])->first();

            if($page_detail != null)
            {
                $page_detail->dislike = $request->dislike;

                $page_detail->save();
            }
            else
            {
                $page_detail = new PageDetail;

                $page_detail->user_id    = Auth::id();
                $page_detail->page_id    = $page_id;
                $page_detail->dislike     = $request->dislike;

                $page_detail->save();
            }

            $user = User::where('id',$page->created_by)->first();

            if($request->dislike === 1)
            {
                $message = trans('messages.unlike_success_msg',['page' => $page->page_name]);


            }
            else
            {
                $message = trans('messages.remove_unlike_success_msg',['page' => $page->page_name]);


            }

            /*if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       =   $user;
                $data['details']    =   $details;

                event(new SingleNotificationEvent($data));
            }*/

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $page,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_UNLIKE_PAGE,
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
