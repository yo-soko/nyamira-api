<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'dashboard',
            'employees',
            'departments',
            'designations',
            'shifts',
            'attendance',
            'students',
            'terms',
            'fees',
            'leaves application',
            'leaves',
            'leaves types',
            'holidays',
            'payroll',
            'users',
            'roles & permissions',
            'delete account request',
        ];

        $actions = ['view', 'add', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $name = strtolower($action . ' ' . $module);
                Permission::firstOrCreate(['name' => $name]);
            }
        }
    }
}

