<?php

namespace App\Http\Controllers\API;

use App\Models\District\District;
use App\Models\State\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    //districts start

    public function districts(Request $request)
    {
        $query = \App\Models\District\District::query();


        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list' => $data, 'status' => 200]);
    }
    //districts end

    //get_district_by_state start

    public function get_district_by_state(Request $request)
    {
        $state_id = $request->state_id;
        $district_list = District::where('state_id', $state_id)->pluck('id', 'district_name');
        return response()->json(['district_list' => $district_list, 'status' => 200]);

    }
    //get_district_by_state end
//End File
}
