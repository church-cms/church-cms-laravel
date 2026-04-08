<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\FaqCategory as FaqCategoryResource;
use App\Http\Requests\FaqCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FaqCategory;
use App\Traits\LogActivity;
use App\Traits\Common;
use Exception;
use Log;

class FaqCategoryController extends Controller
{
    use LogActivity;
    use Common;

    public function index()
    {
        return view('/admin/faq_category/index');
    }

    public function list()
    {
        $categories = FaqCategory::where('church_id', Auth::user()->church_id)
            ->latest()
            ->paginate(10);

        return FaqCategoryResource::collection($categories);
    }

    public function store(FaqCategoryRequest $request)
    {
        try {
            $category = new FaqCategory;
            $category->church_id = Auth::user()->church_id;
            $category->name      = $request->name;
            $category->status    = 1;
            $category->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_ADD_FAQ_CATEGORY,
                'FAQ Category Added Successfully'
            );

            return ['success' => 'FAQ Category Added Successfully'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function editList($id)
    {
        $category = FaqCategory::where('id', $id)
            ->where('church_id', Auth::user()->church_id)
            ->firstOrFail();

        return [
            'id'     => $category->id,
            'name'   => $category->name,
            'status' => $category->status,
        ];
    }

    public function edit($id)
    {
        $category = FaqCategory::where('id', $id)
            ->where('church_id', Auth::user()->church_id)
            ->firstOrFail();

        return view('/admin/faq_category/edit', ['category' => $category]);
    }

    public function update(FaqCategoryRequest $request, $id)
    {
        try {
            $category = FaqCategory::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $category->name   = $request->name;
            $category->status = $request->status ?? $category->status;
            $category->save();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_EDIT_FAQ_CATEGORY,
                'FAQ Category Updated Successfully'
            );

            return ['success' => 'FAQ Category Updated Successfully'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = FaqCategory::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $category->delete();

            $ip = $this->getRequestIP();
            $this->doActivityLog(
                $category,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT']],
                LOGNAME_DELETE_FAQ_CATEGORY,
                'FAQ Category Deleted Successfully'
            );

            return ['success' => 'FAQ Category Deleted Successfully'];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
