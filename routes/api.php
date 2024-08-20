<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/project/apis', function (Request $request) {
    $project = $request->attributes->get('project');

    // Hide the specified fields
    $apiBuilders = $project->apiBuilders->makeHidden([
        'predefined_conditions',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
        'method_name',
        'model'
    ]);

    // Define the key mappings (old_key => new_key)
    $keyMappings = [
        'route_name' => 'api_end_point',
        // Add more key mappings here if needed
    ];

    // Apply key renaming
    $apiBuilders = $apiBuilders->map(function ($item) use ($keyMappings) {
        $newItem = [];
        foreach ($item->toArray() as $key => $value) {
            $newKey = $keyMappings[$key] ?? $key; // Rename key if it exists in the mapping
            $newItem[$newKey] = $value;
        }
        return $newItem;
    });

    return response()->json(['api_list' => $apiBuilders->values(), 'status' => 200]);
})->name('project.apis');


Route::get('/countries', [\App\Http\Controllers\API\CountryController::class, 'countries'])->name('countries');
Route::get('/states', [\App\Http\Controllers\API\StateController::class, 'states'])->name('states');

