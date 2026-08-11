<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::orderBy('name');

        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $countries = $query->paginate(20)->withQueryString();
        return view('admin.masterdata.country.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.masterdata.country.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'iso_code'   => 'nullable|string|max:10',
            'tel_prefix' => 'nullable|string|max:10',
            'status'     => 'required|in:0,1',
        ]);

        Country::create($request->only('name', 'short_name', 'iso_code', 'tel_prefix', 'status'));

        return redirect('/admin/countries')->with('success', 'Country added successfully.');
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.masterdata.country.edit', compact('country'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'short_name' => 'nullable|string|max:50',
            'iso_code'   => 'nullable|string|max:10',
            'tel_prefix' => 'nullable|string|max:10',
            'status'     => 'required|in:0,1',
        ]);

        Country::findOrFail($id)->update($request->only('name', 'short_name', 'iso_code', 'tel_prefix', 'status'));

        return redirect('/admin/countries')->with('success', 'Country updated successfully.');
    }

    public function destroy($id)
    {
        Country::findOrFail($id)->delete();
        return redirect('/admin/countries')->with('success', 'Country deleted.');
    }

    public function bulk(Request $request)
    {
        $selectAll = $request->input('select_all') === '1';

        $request->validate([
            'action' => 'required|in:activate,deactivate',
            'ids'    => ($selectAll ? 'nullable' : 'required') . '|array',
            'ids.*'  => 'integer|exists:countries,id',
        ]);

        if ($selectAll) {
            $query = Country::query();
            if ($request->filled('filter_status') && $request->filter_status !== '') {
                $query->where('status', $request->filter_status);
            }
        } else {
            $query = Country::whereIn('id', $request->ids);
        }

        $query->update(['status' => $request->action === 'activate' ? 1 : 0]);

        return redirect('/admin/countries')->with('success', 'Countries updated successfully.');
    }
}
