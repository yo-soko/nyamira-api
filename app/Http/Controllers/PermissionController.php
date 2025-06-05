<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function setRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        session(['selected_role_id' => $request->role_id]);

        return redirect()->route('permissions');
    }


    public function index()
    {
        $roleId = session('selected_role_id');

        if (!$roleId) {
            return redirect()->back()->with('error', 'No role selected.');
        }

        $role = Role::findOrFail($roleId);

        // Get all permissions and group them
        $permissions = Permission::all();

        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            $parts = explode(' ', $permission->name, 2); // e.g. 'view users' → ['view', 'users']
            if (count($parts) === 2) {
                [$action, $module] = $parts;
                $groupedPermissions[$module][strtolower($action)] = $permission->name;
            }
        }

        return view('permissions', compact('role', 'groupedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $permissions = $request->input('permissions', []);

        // Sync permissions to the role
        $role->syncPermissions($permissions);

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

}
