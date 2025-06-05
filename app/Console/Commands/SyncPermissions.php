<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class SyncPermissions extends Command
{
    protected $signature = 'permission:sync';
    protected $description = 'Syncs default permissions without affecting existing data';

    public function handle()
    {
        $modules = [
            'Dashboard',
            'Products',
            'Category',
            'Sub Category',
            'Brands',
            'Units',
            'Warranties',
            'Manage Stock',
            'Stock Adjustment',
            'Stock Movement',
            'Sales Orders',
            'Purchase Dashboard',
            'Purchases',
            'Purchase Order',
            'Expenses',
            'Expense Category',
            'Income',
            'Income Category',
            'Customers',
            'Suppliers',
            'Stores',
            'Users',
            'Roles & Permissions',
            'Delete Account Request',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        $this->info(" Syncing permissions...");

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $name = strtolower($action . ' ' . $module);
                Permission::firstOrCreate(['name' => $name]);
            }
        }

        $this->info(" Permissions synced successfully.");
    }
}
