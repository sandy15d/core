<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $results = Permission::orderBy('group_name')->get();

        $grouped_results = $results->mapToGroups(function ($item, $key) {
            return [$item->group_name => $item->name];
        });
        $permissions = $grouped_results->toArray();
        return view('permission.permission_list', compact('permissions'));
    }
}
