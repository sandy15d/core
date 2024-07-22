<?php

namespace App\Http\Controllers\District;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District\District;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function index()
    {
        $district_list = District::all();
        return view('district.district_list', compact('district_list'));
    }

    public function create()
    {
        $data = new District();
        $state_list = DB::table('state')->get();
        return view('district.district_form', compact('data', 'state_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'district_name' => 'required',
            'district_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      District::create($request->all()); 
     return redirect()->route('district.index')->with('toast_success', 'District Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = District::findOrFail($id);
        $state_list = DB::table('state')->get();
        return view('district.district_form', compact('data', 'state_list'));
    }

    public function update(Request $request, District $district)
    {
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'district_name' => 'required',
            'district_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $district->update($request->all());
     return redirect()->route('district.index')->with('toast_success', 'District Updated Successfully!');
    }

    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('district.index')->with('toast_success', 'District Deleted Successfully!');
    }
}
