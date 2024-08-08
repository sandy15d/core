<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\DeptSubDeptMapping;
use App\Models\Department\Department;
use App\Models\SubDepartment\SubDepartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeptSubDeptMappingController extends Controller
{
    public function index()
    {
        $Department_list = Department::pluck('department_name', 'id');
        $SubDepartment_list = SubDepartment::select(['sub_department_name', 'sub_department_code', 'numeric_code', 'id'])->get();
        return view("Mapping.DeptSubDeptMapping.index", compact('Department_list', 'SubDepartment_list'));
    }

    public function dept_sub_dept_mappings_data(Request $request){
        $effective_from = Carbon::parse($request->effective_from);
        $department_id = $request->department_id;
        $sub_department_ids = $request->sub_department_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;
        // Prepare the data for batch insert
    $insertData = array_map(function($sub_department_id) use ( $department_id, $effective_from, $userId, $currentTimestamp) {
        return [
            'department_id' =>  $department_id,
            'sub_department_id' => $sub_department_id,
            'effective_from' => $effective_from,
            'created_by' => $userId,
            'created_at' => $currentTimestamp,
        ];
    }, $sub_department_ids);

    try {
        // Use a transaction to ensure data integrity
        DB::transaction(function() use ($department_id, $sub_department_ids, $effective_from, $insertData) {
            foreach ($sub_department_ids as $sub_department_id) {
                // Find any existing mapping with the same department_id and sub_department_id
                $existingMapping = DeptSubDeptMapping::where('sub_department_id', $sub_department_id)
                    ->whereNull('effective_to')
                    ->orderBy('effective_from', 'desc')
                    ->first();

                if ($existingMapping && $existingMapping->effective_from < $effective_from) {
                    // Update the effective_to date of the existing mapping
                    $existingMapping->effective_to = $effective_from->copy()->subDay();
                    $existingMapping->updated_at = Carbon::now();
                    $existingMapping->save();
                }
            }

            // Insert the new mappings
            DeptSubDeptMapping::insert($insertData);
        });
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function dept_sub_dept_mappings_list(){
        $list = DeptSubDeptMapping::all();
        return view("Mapping.DeptSubDeptMapping.list",compact('list'));
    }
}