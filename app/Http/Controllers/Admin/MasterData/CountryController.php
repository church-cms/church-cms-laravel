<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->paginate(20);
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
}
