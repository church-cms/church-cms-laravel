<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use App\Models\PrayerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrayerCategoryController extends Controller
{
    public function index()
    {
        $churchId   = Auth::user()->church_id;
        $categories = PrayerCategory::forChurch($churchId)->ordered()->get();

        return view('admin.prayerboard.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.prayerboard.categories.create');
    }

    public function store(Request $request)
    {
        $churchId = Auth::user()->church_id;

        $request->validate([
            'name'           => [
                'required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('prayer_categories')->where('church_id', $churchId),
            ],
            'css_class'      => 'required|string|max:50',
            'emoji'          => 'required|string|max:10',
            'display_color'  => 'required|string|size:7',
            'gradient_start' => 'required|string|size:7',
            'gradient_end'   => 'required|string|size:7',
            'sort_order'     => 'required|integer|min:0',
            'is_active'      => 'boolean',
            'description'    => 'nullable|string|max:500',
        ]);

        PrayerCategory::create([
            'church_id'      => $churchId,
            'name'           => $request->name,
            'css_class'      => $request->css_class,
            'emoji'          => $request->emoji,
            'display_color'  => $request->display_color,
            'gradient_start' => $request->gradient_start,
            'gradient_end'   => $request->gradient_end,
            'sort_order'     => $request->sort_order,
            'is_active'      => $request->boolean('is_active', true),
            'description'    => $request->description,
            'created_by'     => Auth::id(),
            'updated_by'     => Auth::id(),
        ]);

        return redirect('/admin/prayercategories')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $churchId = Auth::user()->church_id;
        $category = PrayerCategory::forChurch($churchId)->findOrFail($id);

        return view('admin.prayerboard.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $churchId = Auth::user()->church_id;
        $category = PrayerCategory::forChurch($churchId)->findOrFail($id);

        $request->validate([
            'name'           => [
                'required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('prayer_categories')->where('church_id', $churchId)->ignore($id),
            ],
            'css_class'      => 'required|string|max:50',
            'emoji'          => 'required|string|max:10',
            'display_color'  => 'required|string|size:7',
            'gradient_start' => 'required|string|size:7',
            'gradient_end'   => 'required|string|size:7',
            'sort_order'     => 'required|integer|min:0',
            'is_active'      => 'boolean',
            'description'    => 'nullable|string|max:500',
        ]);

        $category->update([
            'name'           => $request->name,
            'css_class'      => $request->css_class,
            'emoji'          => $request->emoji,
            'display_color'  => $request->display_color,
            'gradient_start' => $request->gradient_start,
            'gradient_end'   => $request->gradient_end,
            'sort_order'     => $request->sort_order,
            'is_active'      => $request->boolean('is_active', true),
            'description'    => $request->description,
            'updated_by'     => Auth::id(),
        ]);

        return redirect('/admin/prayercategories')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $churchId = Auth::user()->church_id;
        $category = PrayerCategory::forChurch($churchId)->findOrFail($id);

        if (!$category->canBeDeleted()) {
            return redirect('/admin/prayercategories')
                ->with('error', 'Cannot delete a category that has active or pending prayers.');
        }

        $category->delete();

        return redirect('/admin/prayercategories')->with('success', 'Category deleted.');
    }
}
