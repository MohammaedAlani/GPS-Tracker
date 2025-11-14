<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController
{
    public function index()
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
        ]);

        Role::create([
            'name' => $credentials['name'],
        ]);

        return redirect()->route('role.index');
    }

    public function edit(int $role)
    {
        $role = Role::findById($role);
        $permissions = Permission::all();
        return view('role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, int $role)
    {
        $role = Role::findById($role);
        $credentials = $request->validate([
            'name' => 'required',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role->update([
            'name' => $credentials['name'],
        ]);

        $validPermissions = Permission::where('guard_name', $role->guard_name)
            ->whereIn('id', $request->input('permissions', []))
            ->get();

        $role->syncPermissions($validPermissions);

        return redirect()->route('role.index');
    }

    public function destroy(int $role)
    {
        $role = Role::findById($role);
        $role->delete();
        return redirect()->route('role.index');
    }
}
