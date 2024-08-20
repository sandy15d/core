<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/project/apis', function (Request $request) {
    $project = $request->attributes->get('project');
    // Hide the specified fields
    $apiBuilders = $project->apiBuilders->makeHidden(['predefined_conditions', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by']);
    return response()->json(['api_list' => $apiBuilders, 'status' => 200]);
})->name('project.apis');
