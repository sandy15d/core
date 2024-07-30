<?php

namespace App\Http\Controllers\Zone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Zone\Zone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    public function index()
    {
        $zone_list = Zone::all();
        return view('zone.zone_list', compact('zone_list'));
    }

    public function create()
    {
        $data = new Zone();
        $vertical_list = DB::table('vertical')->get();
        return view('zone.zone_form', compact('data', 'vertical_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zone_name' => 'required',
            'zone_code' => 'required',
            'effective_date' => 'required',
            'vertical_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Zone::create($request->all()); 
     return redirect()->route('zone.index')->with('toast_success', 'Zone Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Zone::findOrFail($id);
        $vertical_list = DB::table('vertical')->get();
        return view('zone.zone_form', compact('data', 'vertical_list'));
    }

    public function update(Request $request, Zone $zone)
    {
        $validator = Validator::make($request->all(), [
            'zone_name' => 'required',
            'zone_code' => 'required',
            'effective_date' => 'required',
            'vertical_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $zone->update($request->all());
     return redirect()->route('zone.index')->with('toast_success', 'Zone Updated Successfully!');
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('zone.index')->with('toast_success', 'Zone Deleted Successfully!');
    }
}
