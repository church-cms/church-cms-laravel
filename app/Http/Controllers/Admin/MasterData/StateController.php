<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('country')->orderBy('name')->paginate(20);
        return view('admin.masterdata.state.index', compact('states'));
    }

    public function create()
    {
        $countries = Country::where('status', 1)->orderBy('name')->get();
        return view('admin.masterdata.state.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|string|max:100',
            'status'     => 'required|in:0,1',
        ]);

        State::create($request->only('country_id', 'name', 'status'));

        return redirect('/admin/states')->with('success', 'State added successfully.');
    }

    public function edit($id)
    {
        $state     = State::findOrFail($id);
        $countries = Country::where('status', 1)->orderBy('name')->get();
        return view('admin.masterdata.state.edit', compact('state', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|string|max:100',
            'status'     => 'required|in:0,1',
        ]);

        State::findOrFail($id)->update($request->only('country_id', 'name', 'status'));

        return redirect('/admin/states')->with('success', 'State updated successfully.');
    }

    public function destroy($id)
    {
        State::findOrFail($id)->delete();
        return redirect('/admin/states')->with('success', 'State deleted.');
    }

    public function ajaxByCountry(Request $request)
    {
        $states = State::where('country_id', $request->country_id)
                       ->where('status', 1)
                       ->orderBy('name')
                       ->get(['id', 'name']);
        return response()->json($states);
    }
}
