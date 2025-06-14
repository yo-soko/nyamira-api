<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(LeaveTypeSeeder::class);
        \App\Models\Department::factory()->count(2)->create();
        \App\Models\Designation::factory()->count(2)->create();
        \App\Models\Shift::factory()->count(2)->create();
        \App\Models\Employee::factory()->count(2)->create();
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call([
            ClassLevelsTableSeeder::class,
            StreamsTableSeeder::class,
            ClassesTableSeeder::class,
            SubjectsTableSeeder::class,
            TermsTableSeeder::class,
            GuardiansTableSeeder::class,
            AttendanceTableSeeder::class,
        ]);
    }
}
