<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/project/apis', function (Request $request) {
    $project = $request->attributes->get('project');

    // Hide the specified fields, including the pivot attribute
    $apiBuilders = $project->apiBuilders->each(function ($apiBuilder) {
        $apiBuilder->makeHidden(['predefined_conditions', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by']);
        $apiBuilder->pivot->setHidden(['pivot']);
    });

    return response()->json(['api_list' => $apiBuilders, 'status' => 200]);
})->name('project.apis');


Route::get('/countries', [\App\Http\Controllers\API\CountryController::class, 'countries'])->name('countries');
Route::get('/states', [\App\Http\Controllers\API\StateController::class, 'states'])->name('states');

