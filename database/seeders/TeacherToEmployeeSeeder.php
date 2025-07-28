<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;       // Assuming you have a Role model for designations
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TeacherToEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed roles/designations if not existing
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator role'],
            ['name' => 'superadmin', 'description' => 'Super Administrator'],
            ['name' => 'developer', 'description' => 'Developer'],
            ['name' => 'teacher', 'description' => 'Teacher designation'],
        ];

        foreach ($roles as $roleData) {
            \DB::table('designations')->updateOrInsert(
                ['name' => $roleData['name']],
                ['description' => $roleData['description'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // Get designations IDs for later
        $designationAdmin = \DB::table('designations')->where('designation', 'admin')->first();
        $designationSuperadmin = \DB::table('designations')->where('designation', 'superadmin')->first();
        $designationDeveloper = \DB::table('designations')->where('designation', 'developer')->first();
        $designationTeacher = \DB::table('designations')->where('designation', 'teacher')->first();

        // 2. Seed the shift (8:00 AM to 6:30 PM)
        $shift = Shift::updateOrCreate(
            ['shift_name' => 'Day Shift'],
            [
                'start_time' => '08:00:00',
                'end_time' => '18:30:00',
                'status' => true,
                'description' => 'Regular day shift from 8 AM to 6:30 PM',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Seed teachers as employees
        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            Employee::updateOrCreate(
                ['email' => $teacher->email],
                [
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'email' => $teacher->email,
                    'contact_number' => $teacher->phone,
                    'emp_code' => 'EMP' . strtoupper(Str::random(6)),
                    'dob' => $teacher->date_of_birth,
                    'gender' => $teacher->gender,
                    'nationality' => 'Not Specified',  // you can customize or add to teachers table
                    'joining_date' => now()->toDateString(),
                    'shift_id' => $shift->id,
                    'department_id' => $teacher->department_id,
                    'designation_id' => $designationTeacher->id,
                    'status' => true,
                    'address' => $teacher->address,
                    'user_id' => $teacher->user_id,
                    'password' => Hash::make('password123'),  // default password, you may want to generate or handle differently
                ]
            );
        }

        // 4. Seed admin/superadmin/developer users as employees
        $adminUsers = User::whereIn('role', ['admin', 'superadmin', 'developer'])->get();

        foreach ($adminUsers as $user) {
            // Map user role to designation id
            $designationId = null;
            switch ($user->role) {
                case 'admin':
                    $designationId = $designationAdmin->id;
                    break;
                case 'superadmin':
                    $designationId = $designationSuperadmin->id;
                    break;
                case 'developer':
                    $designationId = $designationDeveloper->id;
                    break;
                default:
                    $designationId = $designationAdmin->id; // fallback
            }

            Employee::updateOrCreate(
                ['email' => $user->email],
                [
                    'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                    'last_name' => explode(' ', $user->name)[1] ?? '',
                    'email' => $user->email,
                    'contact_number' => $user->phone ?? '0000000000',
                    'emp_code' => 'EMP' . strtoupper(Str::random(6)),
                    'dob' => now()->subYears(25)->toDateString(), // default placeholder DOB
                    'gender' => 'Other',
                    'nationality' => 'Not Specified',
                    'joining_date' => now()->toDateString(),
                    'shift_id' => $shift->id,
                    'department_id' => null, // If you want to assign departments, update here
                    'designation_id' => $designationId,
                    'status' => true,
                    'address' => null,
                    'user_id' => $user->id,
                    'password' => $user->password, // Keep existing hashed password
                ]
            );
        }
    }
}
