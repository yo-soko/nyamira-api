<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendancies;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoClockOutForgottenEmployees extends Command
{
    protected $signature = 'attendance:autoclockout';
    protected $description = 'Automatically clock out employees who forgot to clock out';

    public function handle()
    {
        $attendances = Attendancies::whereNull('clock_out')
            ->whereDate('date', '<', now()->toDateString())
            ->get();

        foreach ($attendances as $attendance) {
            try {
                if (!$attendance->shift) {
                    Log::warning("No shift found for attendance ID: {$attendance->id}");
                    continue;
                }

                $shiftEnd = Carbon::parse($attendance->shift->end_time);
                $shiftStart = Carbon::parse($attendance->shift->start_time);

                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay();
                }

                $clockOutTime = $shiftEnd->copy()->subHours(5);
                $clockInTime = Carbon::parse($attendance->clock_in);

                $workDuration = $clockInTime->diffInSeconds($clockOutTime);

                $breakDuration = 0;
                if ($attendance->break_start && $attendance->break_end) {
                    $breakStart = Carbon::parse($attendance->break_start);
                    $breakEnd = Carbon::parse($attendance->break_end);
                    if ($breakEnd->greaterThan($breakStart)) {
                        $breakDuration = $breakEnd->diffInSeconds($breakStart);
                    }
                }

                $workDuration -= $breakDuration;

                $attendance->clock_out = $clockOutTime;
                $attendance->total_hours = gmdate("H:i:s", $workDuration);
                $attendance->save();

                $this->info("Auto clocked out attendance ID {$attendance->id}");

            } catch (\Exception $e) {
                Log::error("Error auto clocking out ID {$attendance->id}: " . $e->getMessage());
            }
        }

        return 0;
    }
}
