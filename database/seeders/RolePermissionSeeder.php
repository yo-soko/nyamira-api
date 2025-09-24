<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $developer = Role::firstOrCreate(['name' => 'developer']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        $allPermissions = Permission::all();

        // Assign all permissions to developer and admin
        $developer->syncPermissions($allPermissions);
        $admin->syncPermissions($allPermissions);

        // Assign a few to the user role
        $employee->syncPermissions(['view dashboard']);

        // Assign developer role to developer user
        $devUser = \App\Models\User::where('email', 'info@javapa.com')->first();
        if ($devUser) {
            $devUser->syncRoles([$developer]);
        }
    }
}

