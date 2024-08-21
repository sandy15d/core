<?php

namespace App\Http\Controllers\API;

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
        return response()->json(['list'=>$data,'status'=>200]);
    }
    //districts end
//End File
}