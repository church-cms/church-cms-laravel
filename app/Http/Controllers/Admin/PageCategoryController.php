<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\PageCategory as PageCategoryResource;
use App\Http\Requests\PageCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PageCategory;
use App\Traits\LogActivity;
use App\Traits\Common;
use Exception;
use Log;

/**
 * PageCategoryController
 *
 * Manages page categories for organizing static content.
 * Handles category creation, updates, and deletion for page organization.
 * Supports categorizing pages by topic/purpose.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for helper functions
 */
class PageCategoryController extends Controller
{
    //
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/page_category/index');
    }

    public function list()
    {
        $categories = PageCategory::where('church_id', Auth::user()->church_id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return PageCategoryResource::collection($categories);
    }

    public function store(PageCategoryRequest $request)
    {
        try {
            $category = new PageCategory;
            $category->church_id  = Auth::user()->church_id;
            $category->name       = $request->name;
            $category->slug       = $request->slug ?: \Illuminate\Support\Str::slug($request->name);
            $category->description = $request->description;
            $category->sort_order = $request->sort_order ?? 0;
            $category->status     = 1;
            $category->save();

            $res['success'] = trans('messages.add_success_msg', ['module' => 'Page Category']);
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function editList($id)
    {
        $category = PageCategory::where([
            ['church_id', Auth::user()->church_id],
            ['id', $id],
        ])->firstOrFail();

        return [
            'name'        => $category->name,
            'slug'        => $category->slug,
            'description' => $category->description,
            'sort_order'  => $category->sort_order,
            'status'      => $category->status,
        ];
    }

    public function edit($id)
    {
        $category = PageCategory::where([
            ['church_id', Auth::user()->church_id],
            ['id', $id],
        ])->firstOrFail();

        return view('/admin/page_category/edit', ['category' => $category]);
    }

    public function update(PageCategoryRequest $request, $id)
    {
        try {
            $category = PageCategory::where([
                ['church_id', Auth::user()->church_id],
                ['id', $id],
            ])->firstOrFail();

            $category->name        = $request->name;
            $category->slug        = $request->slug ?: \Illuminate\Support\Str::slug($request->name);
            $category->description = $request->description;
            $category->sort_order  = $request->sort_order ?? 0;
            $category->status      = $request->status ?? $category->status;
            $category->save();

            $res['success'] = trans('messages.update_success_msg', ['module' => 'Page Category']);
            return $res;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = PageCategory::where([
                ['church_id', Auth::user()->church_id],
                ['id', $id],
            ])->firstOrFail();

            $category->delete();

            return ['success' => trans('messages.delete_success_msg', ['module' => 'Page Category'])];
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
