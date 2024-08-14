<?php

namespace App\Http\Controllers\EmploymentType;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmploymentType\EmploymentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmploymentTypeController extends Controller
{
    public function index()
    {
        $employment_type_list = EmploymentType::all();
        return view('employment_type.employment_type_list', compact('employment_type_list'));
    }

    public function create()
    {
        $data = new EmploymentType();
        return view('employment_type.employment_type_form', compact('data'));
    }

    public function store(Request $request)
    {
      EmploymentType::create($request->all()); 
     return redirect()->route('employment_type.index')->with('toast_success', 'EmploymentType Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = EmploymentType::findOrFail($id);
        return view('employment_type.employment_type_form', compact('data'));
    }

    public function update(Request $request, EmploymentType $employment_type)
    {
        $employment_type->update($request->all());
     return redirect()->route('employment_type.index')->with('toast_success', 'EmploymentType Updated Successfully!');
    }

    public function destroy(EmploymentType $employment_type)
    {
        $employment_type->delete();
        return redirect()->route('employment_type.index')->with('toast_success', 'EmploymentType Deleted Successfully!');
    }
}
