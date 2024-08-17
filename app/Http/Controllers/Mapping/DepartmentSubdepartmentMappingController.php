<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\DepartmentSubdepartmentMapping;
use App\Models\FunVerticalDept\FunVerticalDept;
use App\Models\SubDepartment\SubDepartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DepartmentSubdepartmentMappingController extends Controller
{
    public function index()
    {
        $FunVerticalDept_list = FunVerticalDept::pluck('name', 'id');
        $SubDepartment_list = SubDepartment::select(['sub_department_name', 'sub_department_code', 'id'])->get();
        return view("Mapping.DepartmentSubdepartmentMapping.index", compact('FunVerticalDept_list', 'SubDepartment_list'));
    }

    public function department_subdepartment_mappings_data(Request $request)
    {
        $effectiveFrom = Carbon::parse($request->effective_from);
        $fun_vertical_dept_id = $request->fun_vertical_dept_id;
        $sub_department_ids = $request->sub_department_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;

        // Prepare the data for batch insert
        $insertData = array_map(function($sub_department_id) use ($fun_vertical_dept_id, $effectiveFrom, $userId, $currentTimestamp) {
            return [
                'fun_vertical_dept_id' => $fun_vertical_dept_id,
                'sub_department_id' => $sub_department_id,
                'name'=> getTableColumnValue('fun_vertical_dept_mapping','name',$fun_vertical_dept_id).' - '.getTableColumnValue('sub_department','sub_department_name',$sub_department_id),
                'effective_from' => $effectiveFrom,
                'created_by' => $userId,
                'created_at' => $currentTimestamp,
            ];
        }, $sub_department_ids);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use ($fun_vertical_dept_id, $sub_department_ids, $effectiveFrom, $insertData) {
                

                // Insert the new mappings
                DepartmentSubdepartmentMapping::insert($insertData);
            });

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function department_subdepartment_mappings_list()
    {
        $list = DepartmentSubdepartmentMapping::all();
        return view("Mapping.DepartmentSubdepartmentMapping.list", compact('list'));
    }
}