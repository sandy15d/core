<?php

namespace App\Http\Controllers\API;

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
            return response()->json(['error' => 'Parameter missing', 'details' => $validator->errors(),'status'=>400], 400);
        }
        $query = \App\Models\CityVillage\CityVillage::query();

            if ($request->has('state_id')) {
                $query->where('state_id', $request->input('state_id'));
            }

        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list'=>$data,'status'=>200]);
    }
    //city_village_by_state end
//End File
}