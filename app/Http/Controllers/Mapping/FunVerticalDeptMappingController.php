<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\FunVerticalDeptMapping;
use App\Models\FunctionVertical\FunctionVertical;
use App\Models\Department\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FunVerticalDeptMappingController extends Controller
{
    public function index()
    {
        $FunctionVertical_list = FunctionVertical::pluck('name', 'id');
        $Department_list = Department::select(['department_name', 'department_code', 'id'])->get();
        return view("Mapping.FunVerticalDeptMapping.index", compact('FunctionVertical_list', 'Department_list'));
    }

    public function fun_vertical_dept_mappings_data(Request $request)
    {
        $effectiveFrom = Carbon::parse($request->effective_from);
        $function_vertical_id = $request->function_vertical_id;
        $department_ids = $request->department_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;

        // Prepare the data for batch insert
        $insertData = array_map(function($department_id) use ($function_vertical_id, $effectiveFrom, $userId, $currentTimestamp) {
            return [
                'function_vertical_id' => $function_vertical_id,
                'department_id' => $department_id,
                'name'=> getTableColumnValue('function_vertical_mapping','name',$function_vertical_id).' - '.getTableColumnValue('department','department_name',$department_id),
                'effective_from' => $effectiveFrom,
                'created_by' => $userId,
                'created_at' => $currentTimestamp,
            ];
        }, $department_ids);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use ($function_vertical_id, $department_ids, $effectiveFrom, $insertData) {
                

                // Insert the new mappings
                FunVerticalDeptMapping::insert($insertData);
            });

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function fun_vertical_dept_mappings_list()
    {
        $list = FunVerticalDeptMapping::all();
        return view("Mapping.FunVerticalDeptMapping.list", compact('list'));
    }
}