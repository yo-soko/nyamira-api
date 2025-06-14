<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'admin',
            'developer',
            'class_teacher',
            'teacher',
            'transport_staff',
            'librarian',
            'student',
            'parent',
            'accountant',
            'receptionist',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}

