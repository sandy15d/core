<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index()
    {
        $company_list = Company::all();
        return view('company.company_list', compact('company_list'));
    }

    public function create()
    {
        $data = new Company();
        return view('company.company_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Company::create($request->all()); 
     return redirect()->route('company.index')->with('toast_success', 'Company Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Company::findOrFail($id);
        return view('company.company_form', compact('data'));
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company->update($request->all());
     return redirect()->route('company.index')->with('toast_success', 'Company Updated Successfully!');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('company.index')->with('toast_success', 'Company Deleted Successfully!');
    }
}
