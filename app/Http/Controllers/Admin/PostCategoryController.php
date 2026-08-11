<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\PostCategory as PostCategoryResource;
use App\Http\Requests\PostCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PostCategory;
use App\Traits\LogActivity;
use App\Traits\Common;
use Exception;
use Log;

class PostCategoryController extends Controller
{
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/post_category/index');
    }

    public function list()
    {
        $categories = PostCategory::where('church_id', Auth::user()->church_id)
            ->latest()
            ->paginate(10);

        return PostCategoryResource::collection($categories);
    }

    public function store(PostCategoryRequest $request)
    {
        try {
            $category              = new PostCategory;
            $category->church_id   = Auth::user()->church_id;
            $category->name        = $request->name;
            $category->description = $request->description;
            $category->status      = 1;
            $category->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_POST_CATEGORY,
                'Post Category Added Successfully'
            );

            return ['success' => 'Post Category Added Successfully'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function editList($id)
    {
        $category = PostCategory::where('id', $id)
            ->where('church_id', Auth::user()->church_id)
            ->firstOrFail();

        return [
            'id'          => $category->id,
            'name'        => $category->name,
            'description' => $category->description,
            'status'      => $category->status,
        ];
    }

    public function edit($id)
    {
        $category = PostCategory::where('id', $id)
            ->where('church_id', Auth::user()->church_id)
            ->firstOrFail();

        return view('/admin/post_category/edit', ['category' => $category]);
    }

    public function update(PostCategoryRequest $request, $id)
    {
        try {
            $category = PostCategory::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $category->name        = $request->name;
            $category->description = $request->description;
            $category->status      = $request->status ?? $category->status;
            $category->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_POST_CATEGORY,
                'Post Category Updated Successfully'
            );

            return ['success' => 'Post Category Updated Successfully'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = PostCategory::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $category->delete();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_POST_CATEGORY,
                'Post Category Deleted Successfully'
            );

            return ['success' => 'Post Category Deleted Successfully'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
