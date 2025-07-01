<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\AttendanceReminderNotification;
use Carbon\Carbon;

class SendAttendanceReminders extends Command
{
    protected $signature = 'attendance:send-reminders';
    protected $description = 'Send attendance reminders with voice notifications';

    public function handle()
    {
        $this->processSession('morning');
        $this->processSession('afternoon');
    }

    protected function processSession($sessionType)
    {
        $schedule = config("attendance.reminders.{$sessionType}");
        $now = now();

        if ($this->shouldSendReminder($now, $schedule)) {
            $this->sendNotifications(
                $sessionType,
                $this->isFirstReminder($now, $schedule)
            );
        }
    }

    protected function shouldSendReminder($currentTime, $schedule)
    {
        $start = Carbon::parse($schedule['start']);
        $end = Carbon::parse($schedule['end']);

        return $currentTime->between($start, $end) &&
            $currentTime->diffInMinutes($start) % $schedule['interval'] === 0;
    }

    protected function isFirstReminder($currentTime, $schedule)
    {
        return $currentTime->diffInMinutes(Carbon::parse($schedule['start'])) < $schedule['interval'];
    }

    protected function sendNotifications($sessionType, $isFirstReminder)
    {
        User::role('teacher')->chunk(100, function ($teachers) use ($sessionType, $isFirstReminder) {
            foreach ($teachers as $teacher) {
                if (!$teacher->hasMarkedAttendance($sessionType)) {
                    $teacher->notify(new AttendanceReminderNotification(
                        $sessionType,
                        $isFirstReminder
                    ));
                }
            }
        });
    }
}
