<?php

namespace App\Http\Controllers\API;

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
        return response()->json(['list'=>$data,'status'=>200]);
    }
    //states end
//End File
}