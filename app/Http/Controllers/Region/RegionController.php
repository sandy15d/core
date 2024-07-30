<?php

namespace App\Http\Controllers\Region;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Region\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    public function index()
    {
        $region_list = Region::all();
        return view('region.region_list', compact('region_list'));
    }

    public function create()
    {
        $data = new Region();
        $vertical_list = DB::table('vertical')->get();
        return view('region.region_form', compact('data', 'vertical_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'region_name' => 'required',
            'region_code' => 'required',
            'effective_date' => 'required',
            'vertical_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Region::create($request->all()); 
     return redirect()->route('region.index')->with('toast_success', 'Region Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Region::findOrFail($id);
        $vertical_list = DB::table('vertical')->get();
        return view('region.region_form', compact('data', 'vertical_list'));
    }

    public function update(Request $request, Region $region)
    {
        $validator = Validator::make($request->all(), [
            'region_name' => 'required',
            'region_code' => 'required',
            'effective_date' => 'required',
            'vertical_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $region->update($request->all());
     return redirect()->route('region.index')->with('toast_success', 'Region Updated Successfully!');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('region.index')->with('toast_success', 'Region Deleted Successfully!');
    }
}
