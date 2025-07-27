<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherToEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Seed all teachers to employees
        $teachers = Teacher::all();

        foreach ($teachers as $teacher) {
            Employee::updateOrCreate(
                ['email' => $teacher->email],
                [
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'email' => $teacher->email,
                    'phone' => $teacher->phone,
                    'id_no' => $teacher->id_no,
                    'address' => $teacher->address,
                    'gender' => $teacher->gender,
                    'status' => true,
                    'department_id' => $teacher->department_id,
                    'class_id' => $teacher->class_id,
                    'user_id' => $teacher->user_id,
                ]
            );
        }

        // Add key admin accounts
        $roles = ['admin', 'superadmin', 'developer'];
        foreach ($roles as $role) {
            User::updateOrCreate(
                ['email' => $role . '@yourdomain.com'],
                [
                    'name' => ucfirst($role) . ' User',
                    'code' => strtoupper(Str::random(8)),
                    'email' => $role . '@yourdomain.com',
                    'phone' => '07' . rand(10000000, 99999999),
                    'role' => $role,
                    'password' => Hash::make('password123'), // You can change this
                    'status' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
