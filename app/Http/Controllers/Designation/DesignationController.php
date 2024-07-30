<?php

namespace App\Http\Controllers\Designation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Designation\Designation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function index()
    {
        $designation_list = Designation::all();
        return view('designation.designation_list', compact('designation_list'));
    }

    public function create()
    {
        $data = new Designation();
        return view('designation.designation_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designation_name' => 'required',
            'designation_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Designation::create($request->all()); 
     return redirect()->route('designation.index')->with('toast_success', 'Designation Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Designation::findOrFail($id);
        return view('designation.designation_form', compact('data'));
    }

    public function update(Request $request, Designation $designation)
    {
        $validator = Validator::make($request->all(), [
            'designation_name' => 'required',
            'designation_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $designation->update($request->all());
     return redirect()->route('designation.index')->with('toast_success', 'Designation Updated Successfully!');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designation.index')->with('toast_success', 'Designation Deleted Successfully!');
    }
}
