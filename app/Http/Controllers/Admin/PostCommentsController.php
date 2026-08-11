<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostCommentEditRequest;
use App\Http\Requests\PostCommentAddRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogActivity;
use App\Models\PostComment;
use App\Traits\Common;
use App\Models\Post;
use Exception;
use Log;

/**
 * PostCommentsController
 *
 * Manages comments on user-generated posts.
 * Handles comment creation, updates, deletion, and moderation.
 * Supports nested comment replies and comment-level interactions.
 * Integrates with post interaction tracking and audit logging.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class PostCommentsController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(PostCommentAddRequest $request,$post_id)
    {
        //
        try
        {
            $post = Post::where('id',$post_id)->first();
            $post_reply = new PostComment;

            $post_reply->user_id    = Auth::id();
            $post_reply->entity_id  = $post_id;
            $post_reply->entity_name= 'App\Models\Post';
            $post_reply->comments   = $request->comments;
            $file = $request->attachment;

            if($file != null)
            {
                $path = $this->uploadFile(Auth::user()->school_id.'/posts/'.$post_id.'/comments',$file);
            }
            $post_reply->attachment_file   = $path;
            $post_reply->status     = 1;

            $post_reply->save();

            $message = trans('messages.add_success_msg',['module' => 'Post Comment']);

            /*if($post->entity_name === 'App\Models\User')
            {
                $user    = User::where('id',$post->entity_id)->first();
                $details = trans('notification.post_comment_add_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
            }
            elseif($post->entity_name === 'App\Models\Page')
            {
                $user    = User::where('id',$post->created_by)->first();
                $details = trans('notification.post_comment_add_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
            }
            if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       = $user;
                $data['details']    = $details;

                event(new SingleNotificationEvent($data));
            }*/

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $post_reply,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_COMMENT,
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
    public function editCommentList($comment_id)
    {
        //
        $post_reply = PostComment::where('id',$comment_id)->first();

        $array = [];

        $array['id']                = $post_reply->id;
        $array['comments']          = $post_reply->comments;
        $array['attachment_file']   = $post_reply->attachment_file === null ? '':$post_reply->AttachmentPath;

        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editComment(PostCommentEditRequest $request,$comment_id)
    {
        //
        try
        {
            $post_reply = PostComment::where('id',$comment_id)->first();

            $post_reply->comments = $request->edit_comments;
            $file = $request->attachment_file;

            if($file != null)
            {
                $path = $this->uploadFile(Auth::user()->school_id.'/posts/'.$post_id.'/comments',$file);
            }
            else
            {
                $path = $post_reply->attachment_file;
            }
            $post_reply->attachment_file   = $path;
            $post_reply->status = 1;

            $post_reply->save();

            $message = trans('messages.update_success_msg',['module' => 'Post Comment']);

            /*if($post_reply->post->entity_name === 'App\Models\User')
            {
                $user    = User::where('id',$post_reply->post->entity_id)->first();
                $details = trans('notification.post_comment_update_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
            }
            elseif($post_reply->post->entity_name === 'App\Models\Page')
            {
                $user    = User::where('id',$post_reply->post->created_by)->first();
                $details = trans('notification.post_comment_update_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
            }
            if($user->id != Auth::id())
            {
                $data = [];

                $data['user']       = $user;
                $data['details']    = $details;

                event(new SingleNotificationEvent($data));
            }*/

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $post_reply,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_EDIT_COMMENT,
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
    public function destroy($comment_id)
    {
        //
        try
        {
            $post_reply = PostComment::where('id',$comment_id)->first();
            if($post_reply->user_id === Auth::id())
            {
                $post_reply->status  = 0;
                $post_reply->save();

                $post_reply->delete();

                $message=trans('messages.delete_success_msg',['module' => 'Post Comment']);

                /*if($post_reply->post->entity_name === 'App\Models\User')
                {
                    $user    = User::where('id',$post_reply->post->entity_id)->first();
                    $details = trans('notification.post_comment_delete_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Post']);
                }
                elseif($post_reply->post->entity_name === 'App\Models\Page')
                {
                    $user    = User::where('id',$post_reply->post->created_by)->first();
                    $details = trans('notification.post_comment_delete_success_msg',['user' => Auth::user()->FullName , 'entity' => 'Page']);
                }
                if($user->id != Auth::id())
                {
                    $data = [];

                    $data['user']       = $user;
                    $data['details']    = $details;

                    event(new SingleNotificationEvent($data));
                }*/

                $ip= $this->getRequestIP();
                $this->doActivityLog(
                    $post_reply,
                    Auth::user(),
                    ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                    LOGNAME_DELETE_COMMENT,
                    $message
                );
                $res['success'] = $message;
                return $res;
            }
            else
            {
                abort(403);
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
