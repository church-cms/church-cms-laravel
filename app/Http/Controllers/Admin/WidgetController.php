<?php

namespace App\Http\Controllers\Admin;

use App\Models\Widget;
use App\Models\Church;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\WidgetRequest;
use Illuminate\Support\Str;

/**
 * WidgetController
 *
 * Manages dashboard widgets and customizable dashboard components.
 * Handles widget creation, configuration, and dashboard layout management.
 * Supports personalized dashboard customization for users.
 *
 * @package App\Http\Controllers\Admin
 */
class WidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Widget::query()->with('userInfo');
        $pages = (isset($request->page) ? $request->page : '');
        $query_string = array();
        $getWidgets = $query->orderBy('id', 'desc')->paginate(10);
        $build = http_build_query($query_string);
        return view('admin.widgets.index', compact('getWidgets', 'pages', 'build'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $church = Church::where('status', 1)->get();
        return view('admin.widgets.form', compact('church'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WidgetRequest $request)
    {
        $uuid = Str::uuid()->toString();
        $insertWidget = new Widget();
        $insertWidget->slug = $uuid;
        $insertWidget->church_id = Auth::user()->church_id;
        $insertWidget->page = $request->input('page', 'home');
        if ($request->input('page', 'home') != 'home') {
            $insertWidget->position = $request->position;
        }

        $insertWidget->display_order = $request->input('display_order', 0);
        $insertWidget->content = $request->content;
        $insertWidget->created_by = Auth::user()->id;
        $insertWidget->save();

        return redirect('admin/widgets')->with('successmessage', 'Widget has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Widget  $widget
     * @return \Illuminate\Http\Response
     */
    public function show(Widget $widget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Widget  $widget
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editInfo = Widget::find($id);
        if (!empty($editInfo) === 0) {
            return redirect('admin/widgets')->with('error', __('common.no_records_found'));
        }
        $church = Church::where('status', 1)->get();
        return view('admin.widgets.form_edit', compact('editInfo', 'church'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Widget  $widget
     * @return \Illuminate\Http\Response
     */
    public function update(WidgetRequest $request, $id)
    {
        $updateWidget = Widget::find($id);
        $updateWidget->church_id = Auth::user()->church_id;
        $updateWidget->page = $request->input('page', 'home');
        $updateWidget->display_order = $request->input('display_order', 0);
        if ($request->input('page', 'home') != 'home') {
            $updateWidget->position = $request->position;
        }
        $updateWidget->content = $request->content;
        $updateWidget->updated_by = Auth::user()->id;
        $updateWidget->save();

        return redirect('admin/widgets')->with('successmessage', 'Widget has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Widget  $widget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Widget $widget)
    {
        //
    }
}
