<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class WorkTicketPermissionSeeder extends Seeder
{
    public function run()
    {
        $module = 'work-ticket';

        // Standard actions + one custom
        $actions = ['view', 'add', 'edit', 'delete', 'approve'];

        foreach ($actions as $action) {
            $name = strtolower($action . ' ' . $module);
            Permission::firstOrCreate(['name' => $name]);
        }
    }
}
