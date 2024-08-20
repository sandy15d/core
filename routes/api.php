<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/project/apis', function (Request $request) {
    $project = $request->attributes->get('project');
    // Hide the specified fields
    $apiBuilders = $project->apiBuilders->makeHidden(['predefined_conditions', 'created_at', 'updated_at', 'deleted_at', 'created_by',
        'updated_by', 'deleted_by', 'method_name', 'model'
    ]);

    // Define the key mappings (old_key => new_key)
    $keyMappings = [
        'route_name' => 'api_end_point',
        // Add more key mappings here if needed
    ];

    // Define the desired key order
    $desiredKeyOrder = ['id', 'api_name', 'api_end_point', 'description', 'parameters'];

    // Apply key renaming and rearrange the order of keys
    $apiBuilders = $apiBuilders->map(function ($item) use ($keyMappings, $desiredKeyOrder) {
        // Apply key renaming
        $newItem = [];
        foreach ($item->toArray() as $key => $value) {
            $newKey = $keyMappings[$key] ?? $key;
            $newItem[$newKey] = $value;
        }

        // Rearrange the keys according to the desired order
        $sortedItem = [];
        foreach ($desiredKeyOrder as $key) {
            if (array_key_exists($key, $newItem)) {
                $sortedItem[$key] = $newItem[$key];
            }
        }

        return $sortedItem;
    });

    return response()->json(['api_list' => $apiBuilders->values(), 'status' => 200]);
})->name('project.apis');


Route::get('/countries', [\App\Http\Controllers\API\CountryController::class, 'countries'])->name('countries');
Route::get('/states', [\App\Http\Controllers\API\StateController::class, 'states'])->name('states');

