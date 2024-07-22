<?php

namespace App\Http\Controllers\Department;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $department_list = Department::all();
        return view('department.department_list', compact('department_list'));
    }

    public function create()
    {
        $data = new Department();
        return view('department.department_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required',
            'department_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Department::create($request->all()); 
     return redirect()->route('department.index')->with('toast_success', 'Department Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Department::findOrFail($id);
        return view('department.department_form', compact('data'));
    }

    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'department_name' => 'required',
            'department_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $department->update($request->all());
     return redirect()->route('department.index')->with('toast_success', 'Department Updated Successfully!');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('department.index')->with('toast_success', 'Department Deleted Successfully!');
    }
}
