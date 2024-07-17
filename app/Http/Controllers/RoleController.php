<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role_list = Role::where('name', '!=', 'Super Admin')->get();
        $results = Permission::orderBy('group_name')->get();
        $grouped_results = $results->mapToGroups(function ($item, $key) {
            return [$item->group_name => ['name' => $item->name, 'id' => $item->id]];
        });
        $permissions = $grouped_results->toArray();
        return view('role.role_list', compact('role_list', 'permissions'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'role_name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('role_name'), 'status' => 'active']);

        $id = $role->id;
        $permissions = $request->input('permission');


        foreach ($permissions as $permission) {
            DB::table('role_has_permissions')->insert(['role_id' => $id, 'permission_id' => $permission]);
        }

        return redirect()->route('role.index')
            ->with('toast_success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        $results = Permission::orderBy('group_name')->get();
        $grouped_results = $results->mapToGroups(function ($item, $key) {
            return [$item->group_name => ['name' => $item->name, 'id' => $item->id]];
        });
        $permissions = $grouped_results->toArray();

        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();


        return view('role.role_form', compact('role', 'rolePermissions', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this->validate($request, [
            'role_name' => 'required|unique:roles,name,' . $id,
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('role_name');
        $role->save();
        //delete old permission
        DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)->delete();
        $permissions = $request->input('permission');


        foreach ($permissions as $permission) {
            DB::table('role_has_permissions')->insert(['role_id' => $id, 'permission_id' => $permission]);
        }

        return redirect()->route('role.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        $role->delete();

        return response()->json(['status' => 200, 'message' => 'Data Deleted Successfully.']);
    }
}
