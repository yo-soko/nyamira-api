<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles-permissions', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);


        return redirect()->back()->with('success', 'Role created successfully.');
    }

        public function destroy($id)
        {
            Role::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Role deleted.');
        }

        public function toggleStatus($id)
        {
            $role = Role::findOrFail($id);
            $role->status = $role->status === 'active' ? 'inactive' : 'active';
            $role->save();
            return redirect()->back()->with('success', 'Status updated.');
        }
    }
