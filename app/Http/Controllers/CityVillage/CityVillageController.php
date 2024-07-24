<?php

namespace App\Http\Controllers\CityVillage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CityVillage\CityVillage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CityVillageController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the search term from the request
        $search = $request->input('search');

        // Initialize the query builder for CityVillage model
        $data = CityVillage::query();

        // Apply the search filter if the search term is not empty
        if (!empty($search)) {
            $data->where(function($query) use ($search) {
                $query->where('city_village_name', 'like', '%' . $search . '%')
                    ->orWhere('division_name', 'like', '%' . $search . '%')
                    ->orWhere('pincode', 'like', '%' . $search . '%');
            });
        }

        // Paginate the results with 10 items per page
        $city_village_list = $data->paginate(10);

        // Append the search term to the pagination links if it exists
        if (!empty($search)) {
            $city_village_list->appends(['search' => $search]);
        }

        // Return the view with the paginated list of city villages
        return view('city_village.city_village_list', compact('city_village_list'));
    }


    public function create()
    {
        $data = new CityVillage();
        $district_list = DB::table('district')->get();
        $state_list = DB::table('state')->get();
        return view('city_village.city_village_form', compact('data', 'district_list', 'state_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_village_name' => 'required',
            'pincode' => 'required',
            'district_id' => 'required',
            'state_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CityVillage::create($request->all());
        return redirect()->route('city_village.index')->with('toast_success', 'CityVillage Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = CityVillage::findOrFail($id);
        $district_list = DB::table('district')->get();
        $state_list = DB::table('state')->get();
        return view('city_village.city_village_form', compact('data', 'district_list', 'state_list'));
    }

    public function update(Request $request, CityVillage $city_village)
    {
        $validator = Validator::make($request->all(), [
            'city_village_name' => 'required',
            'pincode' => 'required',
            'district_id' => 'required',
            'state_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $city_village->update($request->all());
        return redirect()->route('city_village.index')->with('toast_success', 'CityVillage Updated Successfully!');
    }

    public function destroy(CityVillage $city_village)
    {
        $city_village->delete();
        return redirect()->route('city_village.index')->with('toast_success', 'CityVillage Deleted Successfully!');
    }
}
