<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{



    //countries start

    public function countries(Request $request)
    {
        $query = \App\Models\Country\Country::query();


        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list'=>$data,'status'=>200]);
    }
    //countries end
//End File
}