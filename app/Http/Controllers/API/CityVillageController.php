<?php

namespace App\Http\Controllers\API;

use App\Models\CityVillage\CityVillage;
use App\Models\District\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CityVillageController extends Controller
{

    //city_village_by_state start

    public function cityVillageByState(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Parameter missing', 'details' => $validator->errors(), 'status' => 400], 400);
        }
        $query = \App\Models\CityVillage\CityVillage::query();

        if ($request->has('state_id')) {
            $query->where('state_id', $request->input('state_id'));
        }

        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list' => $data, 'status' => 200]);
    }
    //city_village_by_state end


    //get_city_by_district start

    public function get_city_by_district(Request $request)
    {
        $district_id = $request->district_id;
        $city_list = CityVillage::where('district_id', $district_id)->pluck('id', 'city_village_name');
        return response()->json(['city_list' => $city_list, 'status' => 200]);
    }
    //get_city_by_district end


//End File
}
