<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostAttachmentRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostRequest;
use App\Traits\LogActivity;
use App\Traits\Common;
use App\Models\Post;
use Exception;
use Log;

/**
 * PostEditController
 *
 * Handles editing and updating of user-generated forum posts.
 * Manages post content updates and attachment management.
 * Enforces post ownership verification before allowing edits.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class PostEditController extends Controller
{
    //
    use LogActivity;
    use Common;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editList($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->created_by === Auth::id()) {
            $array = [];

            $array['title']             = $post->title;
            $array['description']       = $post->description;

            /*if($post->visibility === 'select_class')
            {
                $array['visible_for']   = $post->visible_for;
            }
            else
            {
            	$array['visible_for']   = '';
            }*/
            $array['post_created_at']   = date('d-m-Y H:i:s', strtotime($post->post_created_at));
            $array['is_posted']         = $post->is_posted;
            if ($post->is_posted === 1) {
                $array['post_later'] = 0;
            } else {
                $array['post_later'] = 1;
            }
            $array['attachment']        = $post->AttachmentPath;

            return $array;
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::where('id', $id)->first();

        if ($post->created_by === Auth::id()) {
            $entity_id      = Auth::id();
            $entity_name    = 'App\Models\User';

            return view('/admin/post/edit', ['post' => $post, 'entity_id' => $entity_id, 'entity_name' => $entity_name]);
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        //
        try {
            $post = Post::where('id', $id)->first();

            $post->title            = $request->title;
            $post->description      = $request->description;

            if ($request->post_later === 'true') {
                $post->post_created_at = date('Y-m-d H:i:s', strtotime($request->posted_at));
                $post->status  = 'pending';
            } else {
                $post->post_created_at = date('Y-m-d H:i:s');
                $post->posted_at = date('Y-m-d H:i:s');
                $post->is_posted = 1;
                $post->status  = 'posted';
            }

            $post->save();

            $message = trans('messages.update_success_msg', ['module' => 'Post']);

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $post,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_POST,
                $message
            );

            $res['success'] = $message;
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editAttachment(PostAttachmentRequest $request, $id)
    {
        //
        try {
            $post = Post::where('id', $id)->first();
            if ($request->attachment_count > 0) {
                $post->attachment_file = null;
                $post->save();
                $initial_path = [];
                for ($j = 0; $j < $request->attachment_count; $j++) {
                    $attachment = 'attachment' . $j;
                    $initial_path[$j] = $request->$attachment;
                }
                $post->attachment_file = $initial_path;
                $post->save();
            }

            $files = $request->file;

            if (count($files) > 0) {
                $i = $request->count + 1;
                $path = [];
                foreach ($files as $file) {
                    $path[$i] = $this->uploadFile(Auth::user()->church_id . '/posts/' . $id, $file);
                    $i++;
                }
                $post->attachment_file = array_merge($post->attachment_file, $path);
                $post->save();
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
