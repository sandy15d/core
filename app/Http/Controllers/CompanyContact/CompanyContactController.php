<?php

namespace App\Http\Controllers\CompanyContact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyContact\CompanyContact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyContactController extends Controller
{
    public function index()
    {
        $company_contact_list = CompanyContact::all();
        return view('company_contact.company_contact_list', compact('company_contact_list'));
    }

    public function create()
    {
        $data = new CompanyContact();
        $company_list = DB::table('company')->get();
        $company_address_list = DB::table('company_address')->get();
        return view('company_contact.company_contact_form', compact('data', 'company_list', 'company_address_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'company_address_id' => 'required',
            'contact_type' => 'required',
            'contact_person' => 'required',
            'phone_one' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      CompanyContact::create($request->all()); 
     return redirect()->route('company_contact.index')->with('toast_success', 'CompanyContact Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = CompanyContact::findOrFail($id);
        $company_list = DB::table('company')->get();
        $company_address_list = DB::table('company_address')->get();
        return view('company_contact.company_contact_form', compact('data', 'company_list', 'company_address_list'));
    }

    public function update(Request $request, CompanyContact $company_contact)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'company_address_id' => 'required',
            'contact_type' => 'required',
            'contact_person' => 'required',
            'phone_one' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company_contact->update($request->all());
     return redirect()->route('company_contact.index')->with('toast_success', 'CompanyContact Updated Successfully!');
    }

    public function destroy(CompanyContact $company_contact)
    {
        $company_contact->delete();
        return redirect()->route('company_contact.index')->with('toast_success', 'CompanyContact Deleted Successfully!');
    }
}
