<?php

namespace App\Http\Controllers\GlobalRegion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GlobalRegion\GlobalRegion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GlobalRegionController extends Controller
{
    public function index()
    {
        $global_region_list = GlobalRegion::all();
        return view('global_region.global_region_list', compact('global_region_list'));
    }

    public function create()
    {
        $data = new GlobalRegion();
        return view('global_region.global_region_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'global_region_name' => 'required',
            'global_region_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      GlobalRegion::create($request->all()); 
     return redirect()->route('global_region.index')->with('toast_success', 'GlobalRegion Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = GlobalRegion::findOrFail($id);
        return view('global_region.global_region_form', compact('data'));
    }

    public function update(Request $request, GlobalRegion $global_region)
    {
        $validator = Validator::make($request->all(), [
            'global_region_name' => 'required',
            'global_region_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $global_region->update($request->all());
     return redirect()->route('global_region.index')->with('toast_success', 'GlobalRegion Updated Successfully!');
    }

    public function destroy(GlobalRegion $global_region)
    {
        $global_region->delete();
        return redirect()->route('global_region.index')->with('toast_success', 'GlobalRegion Deleted Successfully!');
    }
}
