<?php

namespace App\Http\Controllers\BusinessUnit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BusinessUnit\BusinessUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BusinessUnitController extends Controller
{
    public function index()
    {
        $business_unit_list = BusinessUnit::all();
        return view('business_unit.business_unit_list', compact('business_unit_list'));
    }

    public function create()
    {
        $data = new BusinessUnit();
        $vertical_list = DB::table('vertical')->get();
        return view('business_unit.business_unit_form', compact('data', 'vertical_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_unit_name' => 'required',
            'business_unit_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
            'vertical_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      BusinessUnit::create($request->all()); 
     return redirect()->route('business_unit.index')->with('toast_success', 'BusinessUnit Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = BusinessUnit::findOrFail($id);
        $vertical_list = DB::table('vertical')->get();
        return view('business_unit.business_unit_form', compact('data', 'vertical_list'));
    }

    public function update(Request $request, BusinessUnit $business_unit)
    {
        $validator = Validator::make($request->all(), [
            'business_unit_name' => 'required',
            'business_unit_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
            'vertical_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $business_unit->update($request->all());
     return redirect()->route('business_unit.index')->with('toast_success', 'BusinessUnit Updated Successfully!');
    }

    public function destroy(BusinessUnit $business_unit)
    {
        $business_unit->delete();
        return redirect()->route('business_unit.index')->with('toast_success', 'BusinessUnit Deleted Successfully!');
    }
}
