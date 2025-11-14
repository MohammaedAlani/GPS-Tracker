<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
        ]);

        Permission::create([
            'name' => $credentials['name'],
        ]);

        return redirect()->route('permission.index');
    }

    public function edit(int $permission)
    {
        $permission = Permission::findById($permission);
        return view('permission.edit', compact('permission'));
    }

    public function update(Request $request, int $permission)
    {
        $permission = Permission::findById($permission);
        $credentials = $request->validate([
            'name' => 'required',
        ]);
        $permission->update([
            'name' => $credentials['name'],
        ]);
        return redirect()->route('permission.index');
    }

    public function destroy(int $permission)
    {
        $permission = Permission::findById($permission);
        $permission->delete();
        return redirect()->route('permission.index');
    }
}
