<?php

namespace App\Http\Controllers\Api\Angular;

use App\Http\Resources\API\Post as PostResource;
use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Post;
use App\Models\Tag;
use App\Models\PostTag;
use DB;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        //
        $church = Church::where('slug', '=', $slug)->first();

        $posts = Post::where([['church_id', $church->id], ['entity_name', 'App\Models\User'], ['is_posted', 1]])->orderBy('post_created_at', 'DESC')->get();
        $posts = PostResource::collection($posts);

        return $posts;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $id)
    {
        //
        $church = Church::where('slug', '=', $slug)->first();

        $post = Post::where([['church_id', $church->id], ['id', $id]])->with('createdBy')->first();

        $array = [];
        $array['id']                = $post->id;
        $array['title']             = $post->title;
        $array['description']       = $post->description;
        $array['post_created_at']   = $post->post_created_at->diffForHumans();
        $array['created_by']        = $post->createdBy->name;
        $array['is_posted']         = $post->is_posted;
        $array['attachments']       = $post->AttachmentPath;
        $array['like_count']        = $post->postDetail === null ? null : $post->postDetail->ByLikeCount($post->id);
        $array['unlike_count']      = $post->postDetail === null ? null : $post->postDetail->ByUnlikeCount($post->id);
        $array['comment_list']['comments']          = $post->PostComments;
        $array['comment_list']['comments_count']    = count($post->PostComments);
        return $array;
    }

    public function getBlogArchives($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();
        $post =  Post::where('church_id', $church->id)->select(DB::raw("(COUNT(*)) as count"), DB::raw('YEAR(post_created_at) as year'), DB::raw('MONTHNAME(post_created_at) as month'))->groupBy('year', 'month')->get();
        return $post;
    }

    public function getBlogTags($slug)
    {
        $church = Church::where('slug', '=', $slug)->first();
        $post = Post::where('church_id', $church->id)->pluck('id')->toArray();
        $post_tag = PostTag::whereIn('post_id', $post)->pluck('id')->toArray();
        $tag = Tag::whereIn('id', $post_tag)->get();
        return $tag;
    }
}
