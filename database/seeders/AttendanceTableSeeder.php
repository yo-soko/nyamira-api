<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceTableSeeder extends Seeder
{
    public function run(): void
    {
        $date = Carbon::today();

        Attendance::create([
            'student_id' => 1,
            'class_id' => 7,
            'date' => $date,
            'session' => 'AM',
            'status' => 'Present',
            'reason' => null,
        ]);

        Attendance::create([
            'student_id' => 1,
            'class_id' => 6,
            'date' => $date,
            'session' => 'PM',
            'status' => 'Absent',
            'reason' => 'Fever in the afternoon',
        ]);
    }
}
