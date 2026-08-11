<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('country', 'state')->orderBy('name');

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        $cities    = $query->paginate(20)->withQueryString();
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $states    = $request->filled('country_id')
                        ? State::where('country_id', $request->country_id)->where('status', 1)->orderBy('name')->get()
                        : collect();

        return view('admin.masterdata.city.index', compact('cities', 'countries', 'states'));
    }

    public function create()
    {
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $states    = State::where('status', 1)->orderBy('name')->get();
        return view('admin.masterdata.city.create', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id'   => 'required|exists:states,id',
            'name'       => 'required|string|max:100',
            'status'     => 'required|in:0,1',
        ]);

        City::create($request->only('country_id', 'state_id', 'name', 'status'));

        return redirect('/admin/cities')->with('success', 'City added successfully.');
    }

    public function edit($id)
    {
        $city      = City::findOrFail($id);
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $states    = State::where('status', 1)->orderBy('name')->get();
        return view('admin.masterdata.city.edit', compact('city', 'countries', 'states'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id'   => 'required|exists:states,id',
            'name'       => 'required|string|max:100',
            'status'     => 'required|in:0,1',
        ]);

        City::findOrFail($id)->update($request->only('country_id', 'state_id', 'name', 'status'));

        return redirect('/admin/cities')->with('success', 'City updated successfully.');
    }

    public function bulk(Request $request)
    {
        $selectAll = $request->input('select_all') === '1';

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => ($selectAll ? 'nullable' : 'required') . '|array',
            'ids.*'  => 'integer|exists:cities,id',
        ]);

        // Build base query respecting active filters
        if ($selectAll) {
            $query = City::query();
            if ($request->filled('filter_country_id')) {
                $query->where('country_id', $request->filter_country_id);
            }
            if ($request->filled('filter_state_id')) {
                $query->where('state_id', $request->filter_state_id);
            }
        } else {
            $query = City::whereIn('id', $request->ids);
        }

        $count = $query->count();

        switch ($request->action) {
            case 'activate':
                $query->update(['status' => 1]);
                $msg = $count . ' cities activated.';
                break;
            case 'deactivate':
                $query->update(['status' => 0]);
                $msg = $count . ' cities deactivated.';
                break;
            case 'delete':
                $query->delete();
                $msg = $count . ' cities deleted.';
                break;
        }

        $redirect = redirect('/admin/cities');
        if (!$request->select_all && $request->filled('filter_country_id')) {
            $redirect = redirect('/admin/cities?country_id=' . $request->filter_country_id
                . ($request->filled('filter_state_id') ? '&state_id=' . $request->filter_state_id : ''));
        }
        return $redirect->with('success', $msg);
    }

    public function destroy($id)
    {
        City::findOrFail($id)->delete();
        return redirect('/admin/cities')->with('success', 'City deleted.');
    }

    public function ajaxByState(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)
                      ->orderBy('name')
                      ->get(['id', 'name']);
        return response()->json($cities);
    }
}
