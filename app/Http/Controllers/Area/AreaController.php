<?php

namespace App\Http\Controllers\Area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area\Area;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        $area_list = Area::all();
        return view('area.area_list', compact('area_list'));
    }

    public function create()
    {
        $data = new Area();
        return view('area.area_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area_name' => 'required',
            'area_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Area::create($request->all()); 
     return redirect()->route('area.index')->with('toast_success', 'Area Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Area::findOrFail($id);
        return view('area.area_form', compact('data'));
    }

    public function update(Request $request, Area $area)
    {
        $validator = Validator::make($request->all(), [
            'area_name' => 'required',
            'area_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $area->update($request->all());
     return redirect()->route('area.index')->with('toast_success', 'Area Updated Successfully!');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('area.index')->with('toast_success', 'Area Deleted Successfully!');
    }
}
