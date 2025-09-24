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
            'driver',
            'driver-list',
            'driver-history',
            'fuel-history',
            'charging-history',
            'fuel & energy',
            'vehicle',
            'vehicle-history',
            'vehicle-assignment',
            'meter-history',
            'expense-history',
            'replacement-analysis',
            'service',
            'service-history',
            'work-order',
            'service-task',
            'service-program',
            'inspection',
            'inspection-history',
            'failure',
            'schedule',
            'report',
            'standard-report',
            'advanced-analytics',
            'issue',
            'fault',
            'recall',
            'integration',
            'role',
            'permission',       
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

