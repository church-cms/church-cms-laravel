<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Helpers\SiteHelper;
use App\Traits\Common;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Log;

/**
 * PostAddController
 *
 * Handles creation of new user-generated forum posts.
 * Manages post creation with tag association and content moderation.
 * Integrates with post categorization and tag management system.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class PostAddController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createList()
    {
        //
        $posts = Post::whereDate('posted_at',date('Y-m-d'))->get();

        return $posts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if(count(\Request::query()) > 0)
        {
            if($request->entity_id != '')
            {
                $entity_id = $request->entity_id;
            }
            if($request->entity_name != '')
            {
                $entity_name = $request->entity_name;
            }
        }
        else
        {
            $entity_id      = Auth::id();
            $entity_name    = 'App\Models\User';
        }

        return view('/admin/post/create' , [ 'entity_id' => $entity_id , 'entity_name' => $entity_name ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //
        try
        {
            $post = new Post;

            $post->church_id        = Auth::user()->church_id;
            $post->entity_id        = $request->entity_id;
            $post->entity_name      = $request->entity_name;
            $post->title            = $request->title;
            $post->description      = $request->description;

            /*if($request->entity_name === 'App\Models\Page')
            {
                $post->visibility = 'select_page';
            }
            else
            {
                $post->visibility       = $request->visibility;
            }

            if($request->visibility === 'select_class')
            {
                $post->visible_for      = $request->visible_for;
            }*/
            if($request->post_later === 'true')
            {
                $post->post_created_at = date('Y-m-d H:i:s',strtotime($request->posted_at));
                $post->is_posted = 0;
                $post->status  = 'pending';
            }
            else
            {
                $post->post_created_at = date('Y-m-d H:i:s');
                $post->posted_at = date('Y-m-d H:i:s');
                $post->is_posted = 1;
                $post->status  = 'posted';
            }

            $post->created_by = Auth::id();
            $post->save();

            $tags = explode(",", $request->tag);

            $tagObjects=[];

            foreach($tags as $tag)
            {
                $tag=Tag::firstOrCreate(['tag_name' => $tag]);
                array_push($tagObjects,$tag);
            }

            $post->tags()->saveMany($tagObjects);

            $message = trans('messages.add_success_msg',['module' => 'Post']);

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_POST,
                $message
            );

            $res['id'] = $post->id;
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
    public function attachment(Request $request)
    {
        //
        try
        {
            $post = Post::where('id',$request->post_id)->first();
            $i =0;
            $files = $request->file;

            if(count($files) > 0)
            {
                $post->attachment_file = null;
                $post->save();
                $path = [];
                foreach($files as $file)
                {
                    $path[$i] = $this->uploadFile(Auth::user()->church_id.'/posts/'.$request->post_id,$file);
                    $i++;
                }
                $post->attachment_file = $path;
                $post->save();
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
