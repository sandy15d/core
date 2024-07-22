<?php

namespace App\Http\Controllers\SubDepartment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubDepartment\SubDepartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubDepartmentController extends Controller
{
    public function index()
    {
        $sub_department_list = SubDepartment::all();
        return view('sub_department.sub_department_list', compact('sub_department_list'));
    }

    public function create()
    {
        $data = new SubDepartment();
        return view('sub_department.sub_department_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_department_name' => 'required',
            'numeric_code' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      SubDepartment::create($request->all()); 
     return redirect()->route('sub_department.index')->with('toast_success', 'SubDepartment Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = SubDepartment::findOrFail($id);
        return view('sub_department.sub_department_form', compact('data'));
    }

    public function update(Request $request, SubDepartment $sub_department)
    {
        $validator = Validator::make($request->all(), [
            'sub_department_name' => 'required',
            'numeric_code' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sub_department->update($request->all());
     return redirect()->route('sub_department.index')->with('toast_success', 'SubDepartment Updated Successfully!');
    }

    public function destroy(SubDepartment $sub_department)
    {
        $sub_department->delete();
        return redirect()->route('sub_department.index')->with('toast_success', 'SubDepartment Deleted Successfully!');
    }
}
