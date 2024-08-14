<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\ZoneRegionMapping;
use App\Models\Zone\Zone;
use App\Models\Region\Region;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ZoneRegionMappingController extends Controller
{
    public function index()
    {
        $Zone_list = Zone::pluck('zone_name', 'id');
        $Region_list = Region::select(['region_name', 'region_code', 'numeric_code', 'id'])->get();
        return view("Mapping.ZoneRegionMapping.index", compact('Zone_list', 'Region_list'));
    }

    public function zone_region_mappings_data(Request $request)
    {
        $effectiveFrom = Carbon::parse($request->effective_from);
        $zone_id = $request->zone_id;
        $region_ids = $request->region_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;

        // Prepare the data for batch insert
        $insertData = array_map(function($region_id) use ($zone_id, $effectiveFrom, $userId, $currentTimestamp) {
            return [
                'zone_id' => $zone_id,
                'region_id' => $region_id,
                'name'=> getTableColumnValue('zone','zone_name',$zone_id).' - '.getTableColumnValue('region','region_name',$region_id),
                'effective_from' => $effectiveFrom,
                'created_by' => $userId,
                'created_at' => $currentTimestamp,
            ];
        }, $region_ids);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use ($zone_id, $region_ids, $effectiveFrom, $insertData) {
                            foreach ($region_ids as $region_id) {
                $existingMapping = ZoneRegionMapping::where('region_id', $region_id)
                    ->whereNull('effective_to')
                    ->orderBy('effective_from', 'desc')
                    ->first();

                if ($existingMapping && $existingMapping->effective_from < $effectiveFrom) {
                    $existingMapping->effective_to = $effectiveFrom->copy()->subDay();
                    $existingMapping->updated_at = Carbon::now();
                    $existingMapping->save();
                }
            }

                // Insert the new mappings
                ZoneRegionMapping::insert($insertData);
            });

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function zone_region_mappings_list()
    {
        $list = ZoneRegionMapping::all();
        return view("Mapping.ZoneRegionMapping.list", compact('list'));
    }
}