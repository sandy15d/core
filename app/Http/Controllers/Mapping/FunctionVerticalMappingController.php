<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\FunctionVerticalMapping;
use App\Models\OrgFunction\OrgFunction;
use App\Models\Vertical\Vertical;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FunctionVerticalMappingController extends Controller
{
    public function index()
    {
        $OrgFunction_list = OrgFunction::pluck('function_name', 'id');
        $Vertical_list = Vertical::select(['vertical_name', 'vertical_code', 'id'])->get();
        return view("Mapping.FunctionVerticalMapping.index", compact('OrgFunction_list', 'Vertical_list'));
    }

    public function function_vertical_mappings_data(Request $request)
    {
        $effectiveFrom = Carbon::parse($request->effective_from);
        $org_function_id = $request->org_function_id;
        $vertical_ids = $request->vertical_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;

        // Prepare the data for batch insert
        $insertData = array_map(function($vertical_id) use ($org_function_id, $effectiveFrom, $userId, $currentTimestamp) {
            return [
                'org_function_id' => $org_function_id,
                'vertical_id' => $vertical_id,
                'name'=> getTableColumnValue('org_function','function_name',$org_function_id).' - '.getTableColumnValue('vertical','vertical_name',$vertical_id),
                'effective_from' => $effectiveFrom,
                'created_by' => $userId,
                'created_at' => $currentTimestamp,
            ];
        }, $vertical_ids);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use ($org_function_id, $vertical_ids, $effectiveFrom, $insertData) {
                

                // Insert the new mappings
                FunctionVerticalMapping::insert($insertData);
            });

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function function_vertical_mappings_list()
    {
        $list = FunctionVerticalMapping::all();
        return view("Mapping.FunctionVerticalMapping.list", compact('list'));
    }
}