<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $leaveTypes = [
            ['type' => 'Annual Leave', 'quota' => 30, 'status' => true],
            ['type' => 'Sick Leave', 'quota' => 15, 'status' => true],
            ['type' => 'Maternity Leave', 'quota' => 90, 'status' => true],
            ['type' => 'Paternity Leave', 'quota' => 14, 'status' => true],
            ['type' => 'Bereavement Leave', 'quota' => 7, 'status' => true],
            ['type' => 'Study Leave', 'quota' => 20, 'status' => true],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create($type);
        }
    }
}
