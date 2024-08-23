<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrgFunctionController extends Controller
{



    //functions start

    public function functions(Request $request)
    {
        $query = \App\Models\OrgFunction\OrgFunction::query();


        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list'=>$data,'status'=>200]);
    }
    //functions end
//End File
}