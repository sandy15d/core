<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\SubDeptSectionMapping;
use App\Models\SubDepartment\SubDepartment;
use App\Models\Section\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubDeptSectionMappingController extends Controller
{
    public function index()
    {
        $SubDepartment_list = SubDepartment::pluck('sub_department_name', 'id');
        $Section_list = Section::select(['section_name', 'section_code', 'numeric_code', 'id'])->get();
        return view("Mapping.SubDeptSectionMapping.index", compact('SubDepartment_list', 'Section_list'));
    }

    public function sub_dept_section_mappings_data(Request $request){
        $effective_from = Carbon::parse($request->effective_from);
        $sub_department_id = $request->sub_department_id;
        $section_ids = $request->section_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;
        // Prepare the data for batch insert
    $insertData = array_map(function($section_id) use ( $sub_department_id, $effective_from, $userId, $currentTimestamp) {
        return [
            'sub_department_id' =>  $sub_department_id,
            'section_id' => $section_id,
            'effective_from' => $effective_from,
            'created_by' => $userId,
            'created_at' => $currentTimestamp,
        ];
    }, $section_ids);

    try {
        // Use a transaction to ensure data integrity
        DB::transaction(function() use ($sub_department_id, $section_ids, $effective_from, $insertData) {
            foreach ($section_ids as $section_id) {
                // Find any existing mapping with the same sub_department_id and section_id
                $existingMapping = SubDeptSectionMapping::where('section_id', $section_id)
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
            SubDeptSectionMapping::insert($insertData);
        });
            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function sub_dept_section_mappings_list(){
        $list = SubDeptSectionMapping::all();
        return view("Mapping.SubDeptSectionMapping.list",compact('list'));
    }
}