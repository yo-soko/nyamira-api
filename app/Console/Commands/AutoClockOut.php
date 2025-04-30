<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendancies;
use App\Models\Shift;
use Carbon\Carbon;

class AutoClockOut extends Command
{
    protected $signature = 'attendance:auto-clockout';
    protected $description = 'Automatically clock out employees who have not clocked out after their shift + grace period';

    public function handle()
    {
        // Grace period (in minutes)
        $graceMinutes = 30;

        $records = Attendancies::whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->with('shift') // assuming Attendancies model has shift() relationship
            ->get();

        foreach ($records as $record) {
            if (!$record->shift) continue; // skip if no shift found

            $shiftEnd = Carbon::parse($record->date . ' ' . $record->shift->end_time);
            $cutoffTime = $shiftEnd->copy()->addMinutes($graceMinutes);

            if (now()->greaterThanOrEqualTo($cutoffTime)) {
                $record->clock_out = $shiftEnd; // or use now() if you prefer
                $record->save();

                $this->info("Employee ID {$record->employee_id} auto clocked-out at {$shiftEnd->format('H:i')}");
            }
        }

        return Command::SUCCESS;
    }
}

