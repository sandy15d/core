<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\RegionTerritoryMapping;
use App\Models\Region\Region;
use App\Models\Territory\Territory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegionTerritoryMappingController extends Controller
{
    public function index()
    {
        $Region_list = Region::pluck('region_name', 'id');
        $Territory_list = Territory::select(['territory_name', 'territory_code', 'numeric_code', 'id'])->get();
        return view("Mapping.RegionTerritoryMapping.index", compact('Region_list', 'Territory_list'));
    }

    public function region_territory_mappings_data(Request $request)
    {
        $effectiveFrom = Carbon::parse($request->effective_from);
        $region_id = $request->region_id;
        $territory_ids = $request->territory_ids;
        $currentTimestamp = Carbon::now();
        $userId = Auth::user()->id;

        // Prepare the data for batch insert
        $insertData = array_map(function($territory_id) use ($region_id, $effectiveFrom, $userId, $currentTimestamp) {
            return [
                'region_id' => $region_id,
                'territory_id' => $territory_id,
                'name'=> getTableColumnValue('region','region_name',$region_id).' - '.getTableColumnValue('territory','territory_name',$territory_id),
                'effective_from' => $effectiveFrom,
                'created_by' => $userId,
                'created_at' => $currentTimestamp,
            ];
        }, $territory_ids);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use ($region_id, $territory_ids, $effectiveFrom, $insertData) {
                            foreach ($territory_ids as $territory_id) {
                $existingMapping = RegionTerritoryMapping::where('territory_id', $territory_id)
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
                RegionTerritoryMapping::insert($insertData);
            });

            return response()->json(['status' => '200']);
        } catch (\Exception $e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function region_territory_mappings_list()
    {
        $list = RegionTerritoryMapping::all();
        return view("Mapping.RegionTerritoryMapping.list", compact('list'));
    }
}