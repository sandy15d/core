<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{



    //block_by_district start

    public function blockByDistrict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Parameter missing', 'details' => $validator->errors(),'status'=>400], 400);
        }
        $query = \App\Models\Block\Block::query();

            if ($request->has('district_id')) {
                $query->where('district_id', $request->input('district_id'));
            }

        $data = $query->get()->makeHidden(['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by']);
        return response()->json(['list'=>$data,'status'=>200]);
    }
    //block_by_district end
//End File
}