<?php

namespace App\Http\Controllers\OrgFunction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrgFunction\OrgFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrgFunctionController extends Controller
{
    public function index()
    {
        $org_function_list = OrgFunction::all();
        return view('org_function.org_function_list', compact('org_function_list'));
    }

    public function create()
    {
        $data = new OrgFunction();
        return view('org_function.org_function_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'function_name' => 'required',
            'function_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      OrgFunction::create($request->all()); 
     return redirect()->route('org_function.index')->with('toast_success', 'OrgFunction Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = OrgFunction::findOrFail($id);
        return view('org_function.org_function_form', compact('data'));
    }

    public function update(Request $request, OrgFunction $org_function)
    {
        $validator = Validator::make($request->all(), [
            'function_name' => 'required',
            'function_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $org_function->update($request->all());
     return redirect()->route('org_function.index')->with('toast_success', 'OrgFunction Updated Successfully!');
    }

    public function destroy(OrgFunction $org_function)
    {
        $org_function->delete();
        return redirect()->route('org_function.index')->with('toast_success', 'OrgFunction Deleted Successfully!');
    }
}
