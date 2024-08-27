<?php

namespace App\Http\Controllers\API;

use App\Models\State\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{

    //states start

    public function states(Request $request)
    {
        $query = \App\Models\State\State::query();
        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list' => $data, 'status' => 200]);
    }
    //states end


    //get_states_by_country start
    public function get_states_by_country(Request $request)
    {
        $country_id = $request->country_id;
        $state_list = State::where('country_id', $country_id)->pluck('id', 'state_name');
        return response()->json(['state_list' => $state_list, 'status' => 200]);
    }
    //get_states_by_country end


//End File
}
