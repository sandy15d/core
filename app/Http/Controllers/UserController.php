<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $user_list = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Super Admin');
            })
            ->get();
        $roles = Role::where('name', '!=', 'Super Admin')->pluck('name', 'name');
        return view('user.user_list', compact('user_list', 'roles'));
    }

    public function create(Request $request)
    {
        $roles = Role::where('name', '!=', 'Super Admin')->pluck('name', 'name');
        $userRole =[];
        return view('user.user_form', compact('roles','userRole'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required',
            'phone' => 'required|unique:users,phone|min:10',
        ],

            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'role.required' => 'Role is required',
                'phone.required' => 'Phone no is required',
                'phone.unique' => 'Phone no already exists',
            ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $query = new User();
        $query->name = $request->name;
        $query->email = $request->email;
        $query->password = Hash::make($request->phone);
        $query->phone = $request->phone;
        $query->save();
        $query->assignRole($request->role);

        return redirect()->route('user.index')->with('toast_success', 'User created successfully!');
    }

    public function edit(Request $request, User $user)
    {
        $roles = Role::where('name', '!=', 'Super Admin')->pluck('name', 'name');
        $userRole = $user->roles->pluck('name', 'name')->all();
        $data = $user;
        return view('user.user_form', compact('data', 'roles', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',

        ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'role.required' => 'Role is required',

            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->syncRoles($request->role);
        return redirect()->route('user.index')->with('toast_success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('toast_success', 'User deleted successfully!');
    }
}
