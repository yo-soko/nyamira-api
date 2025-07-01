<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Import your command class
use App\Console\Commands\AutoClockOutForgottenEmployees;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AutoClockOutForgottenEmployees::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run daily at 11:55 PM (or change time as needed)
        $schedule->command('attendance:autoclockout')->dailyAt('23:55');

        $schedule->command('attendance:send-reminders')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command('reminders:generate-voice')
            ->weekly()
            ->at('02:00');
    }
    protected $routeMiddleware = [
        // other middleware...
        'admin' => \App\Http\Middleware\Admin::class,
    ];
}
