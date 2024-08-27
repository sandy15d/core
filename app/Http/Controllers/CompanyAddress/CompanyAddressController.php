<?php

namespace App\Http\Controllers\CompanyAddress;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyAddress\CompanyAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyAddressController extends Controller
{
    public function index()
    {
        $company_address_list = CompanyAddress::all();
        return view('company_address.company_address_list', compact('company_address_list'));
    }

    public function create()
    {
        $data = new CompanyAddress();
        $company_list = DB::table('company')->get();
        $country_list = DB::table('country')->get();

        return view('company_address.company_address_form', compact('data', 'company_list', 'country_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'address_type' => 'required',
            'pin_code' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CompanyAddress::create($request->all());
        return redirect()->route('company_address.index')->with('toast_success', 'CompanyAddress Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = CompanyAddress::findOrFail($id);
        $company_list = DB::table('company')->get();
        $country_list = DB::table('country')->get();

        return view('company_address.company_address_form', compact('data', 'company_list', 'country_list'));
    }

    public function update(Request $request, CompanyAddress $company_address)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'address_type' => 'required',
            'pin_code' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $company_address->update($request->all());
        return redirect()->route('company_address.index')->with('toast_success', 'CompanyAddress Updated Successfully!');
    }

    public function destroy(CompanyAddress $company_address)
    {
        $company_address->delete();
        return redirect()->route('company_address.index')->with('toast_success', 'CompanyAddress Deleted Successfully!');
    }

    function get_company_address_by_company(Request $request)
    {
        $company_id = $request->company_id;
        $company_address_type = CompanyAddress::where('company_id', $company_id)->pluck('id', 'address_type');
        return response()->json(['company_address_type' => $company_address_type, 'status' => 200]);
    }
}
