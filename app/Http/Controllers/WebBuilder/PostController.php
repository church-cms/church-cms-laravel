<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $church = request()->attributes->get('_church');

        $categories = PostCategory::withCount(['posts' => function ($q) use ($church) {
                $q->where('is_posted', 1)->where('status', 'posted');
                if ($church) $q->where('church_id', $church->id);
            }])
            ->where('status', 1)
            ->when($church, fn($q) => $q->where('church_id', $church->id))
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $activeCategoryId = request('category');
        $activeTag         = request('tag');

        $posts = Post::with(['category', 'tags'])
            ->where('is_posted', 1)
            ->where('status', 'posted')
            ->when($church, fn($q) => $q->where('church_id', $church->id))
            ->when($activeCategoryId, fn($q) => $q->where('category_id', $activeCategoryId))
            ->when($activeTag, fn($q) => $q->whereHas('tags', fn($tq) => $tq->where('tag_name', $activeTag)))
            ->orderBy('post_created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('theme::post_index', compact('posts', 'categories', 'activeCategoryId', 'activeTag'));
    }

    public function show(Request $request, $id)
    {
        $post = Post::with(['category', 'tags'])->where('id', $id)->where('is_posted', 1)->where('status', 'posted')->firstOrFail();

        $comments = PostComment::with('user')
            ->where('entity_id', $post->id)
            ->where('entity_name', 'App\\Models\\Post')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'comments_page');

        $likedPosts    = $request->session()->get('liked_posts', []);
        $likedComments = $request->session()->get('liked_comments', []);
        $postLiked     = in_array($post->id, $likedPosts);

        return view('theme::post', compact('post', 'comments', 'postLiked', 'likedComments'));
    }

    public function storeComment(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('is_posted', 1)->where('status', 'posted')->firstOrFail();

        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'comment' => 'required|string|max:1000',
        ]);

        PostComment::create([
            'user_id'     => null,
            'guest_name'  => $request->input('name'),
            'guest_email' => $request->input('email'),
            'entity_id'   => $post->id,
            'entity_name' => 'App\\Models\\Post',
            'comments'    => $request->input('comment'),
            'status'      => 0,
        ]);

        return redirect()->route('web.post', $id)
            ->with('comment_success', 'Your comment has been submitted and is awaiting moderation. Thank you!');
    }

    public function toggleLike(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('is_posted', 1)->where('status', 'posted')->firstOrFail();

        $likedPosts = $request->session()->get('liked_posts', []);

        if (in_array($id, $likedPosts)) {
            // Unlike
            $post->decrement('public_like_count');
            $request->session()->put('liked_posts', array_values(array_diff($likedPosts, [$id])));
            $liked = false;
        } else {
            // Like
            $post->increment('public_like_count');
            $likedPosts[] = $id;
            $request->session()->put('liked_posts', $likedPosts);
            $liked = true;
        }

        return response()->json(['liked' => $liked, 'count' => $post->fresh()->public_like_count]);
    }

    public function toggleCommentLike(Request $request, $id)
    {
        $comment = PostComment::where('id', $id)->where('status', 1)->firstOrFail();

        $likedComments = $request->session()->get('liked_comments', []);

        if (in_array($id, $likedComments)) {
            $comment->decrement('public_like_count');
            $request->session()->put('liked_comments', array_values(array_diff($likedComments, [$id])));
            $liked = false;
        } else {
            $comment->increment('public_like_count');
            $likedComments[] = $id;
            $request->session()->put('liked_comments', $likedComments);
            $liked = true;
        }

        return response()->json(['liked' => $liked, 'count' => $comment->fresh()->public_like_count]);
    }
}
