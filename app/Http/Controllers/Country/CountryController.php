<?php

namespace App\Http\Controllers\Country;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function index()
    {
        $country_list = Country::all();
        return view('country.country_list', compact('country_list'));
    }

    public function create()
    {
        $data = new Country();
        return view('country.country_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_name' => 'required',
            'country_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Country::create($request->all()); 
     return redirect()->route('country.index')->with('toast_success', 'Country Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Country::findOrFail($id);
        return view('country.country_form', compact('data'));
    }

    public function update(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(), [
            'country_name' => 'required',
            'country_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $country->update($request->all());
     return redirect()->route('country.index')->with('toast_success', 'Country Updated Successfully!');
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('country.index')->with('toast_success', 'Country Deleted Successfully!');
    }
}
