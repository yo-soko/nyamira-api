<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Your custom commands can go here
    ];

     protected function schedule(Schedule $schedule)
    {
        // run every day at 5 minutes past midnight
        $schedule->call(function () {
            $today = now()->toDateString();

            // fetch all open attendances for today
            $open = Attendancies::whereDate('date', $today)
                ->whereNull('clock_out')
                ->get();

            foreach ($open as $att) {
                $shiftEnd = $att->employee->shift->end_time; 
                // format into HH:MM:SS
                $att->clock_out = Carbon::parse($shiftEnd)->format('H:i:s');
                // recalc total_hours
                $in  = Carbon::parse($att->clock_in);
                $out = Carbon::parse($att->clock_out);
                $att->total_hours = $out->diffInHours($in) . ':' . $out->copy()->subHours($out->diffInHours($in))->format('i:s');
                $att->save();
            }
        })->dailyAt('17:36');
    }

   

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
