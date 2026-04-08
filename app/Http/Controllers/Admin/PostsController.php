<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Post as PostResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Models\PostComment;
use App\Models\PostDetail;
use App\Models\PostTag;
use App\Traits\Common;
use App\Models\Post;
use Exception;
use Log;

/**
 * PostsController
 *
 * Manages user-generated posts and content within the church community.
 * Handles posting, viewing, filtering by entity type, and post interactions (likes/comments).
 * Integrates with activity logging for audit trail, post comments, and tags.
 * Supports polymorphic content association (posts can be attached to various entity types).
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for recording action audit logs
 * @uses Common Trait for file path utilities
 * @see PostComment Model for nested comment functionality
 * @see PostDetail Model for post interaction tracking
 * @see PostTag Model for content tagging
 */
class PostsController extends Controller
{
    use LogActivity;
    use Common;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexList(Request $request)
    {
        //
        $posts = Post::where([['church_id',Auth::user()->church_id],['is_posted',1]])->orderBy('post_created_at','DESC');
        if(count(\Request::query()) > 0)
        {
            if($request->entity_id != '')
            {
                $posts = $posts->where('entity_id',$request->entity_id);
            }
            if($request->entity_name != '')
            {
                $posts = $posts->where('entity_name',$request->entity_name);
            }
        }
        $posts = $posts->get();
        $posts = PostResource::collection($posts);

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::with('category')
            ->where('church_id', Auth::user()->church_id)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->orderBy('post_created_at', 'DESC')
            ->paginate(20)
            ->withQueryString();

        $categories = \App\Models\PostCategory::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();

        return view('/admin/post/index', compact('posts', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showList($id)
    {
        //
        $post = Post::where('id',$id)->first();

        if($post->visibility === 'all_class')
        {
            $visibility = str_replace('_', ' ', ucwords($post->visibility));
        }
        elseif($post->visibility === 'select_class')
        {
            $visibility = $post->StandardLink->StandardSection;
        }

        $array = [];

        $array['id']                = $post->id;
        $array['title']             = $post->title;
        $array['description']       = $post->description;

        $array['post_created_at']   = $post->post_created_at->diffForHumans();
        $array['created_by']        = $post->created_by;
        $array['is_posted']         = $post->is_posted;
        $array['attachments']       = $post->AttachmentPath;
        $post_detail = PostDetail::where([['user_id',Auth::id()],['post_id',$id]])->first();
        $array['like']              = $post_detail->like;
        $array['unlike']            = $post_detail->unlike;
        $array['save']              = $post_detail->save;
        $array['unsave']            = $post_detail->unsave;
        $array['auth_id']           = Auth::id();
        $array['like_count']        = $post->postDetail=== null ?null:$post->postDetail->ByLikeCount($post->id);
        $array['unlike_count']      = $post->postDetail=== null ?null:$post->postDetail->ByUnlikeCount($post->id);
        $array['comment_list']['comments']          = $post->PostComments;
        $array['comment_list']['comments_count']    = count($post->PostComments);

        return $array;
    }

     public function imageList($id)
    {
        //
        $post = Post::where('id',$id)->first();

        $array = [];

        $array['attachments']       = $post->ImagePath;


        return $array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::where('id',$id)->first();

        return view('/admin/post/show' , ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $post = Post::where('id',$id)->first();
            if(Gate::allows('post',$post))
            {
                if($post->created_by === Auth::id())
                {
                    $post->status  = 'cancelled';
                    $post->save();

                    $postdetails = PostDetail::where('post_id',$id)->get();
                    foreach ($postdetails as $postdetail)
                    {
                        $postdetail->delete();
                    }

                    $comments = PostComment::where([['entity_name','App\Models\Post'],['entity_id',$id]])->get();
                    foreach ($comments as $comment)
                    {
                        $replycomments = PostComment::where([['entity_name','App\Models\PostComment'],['entity_id',$comment->id]])->get();
                        foreach ($replycomments as $replycomment)
                        {
                            $replycomment->delete();
                        }
                        $comment->delete();
                    }

                    $posttags = PostTag::where('post_id',$id)->get();
                    foreach ($posttags as $posttag)
                    {
                        $posttag->delete();
                    }

                    $post->delete();

                    $message=trans('messages.delete_success_msg',['module' => 'Post']);

                    $ip= $this->getRequestIP();
                    $this->doActivityLog(
                        $post,
                        Auth::user(),
                        ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                        LOGNAME_DELETE_POST,
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
