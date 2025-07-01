<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AttendanceReminderNotification extends Notification
{
    public function __construct(
        public string $sessionType,
        public bool $isFirstReminder
    ) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->isFirstReminder
                ? "Attendance Time"
                : "Reminder",
            'message' => $this->getMessage(),
            'sound' => $this->getSoundFile(),
            'action_url' => route('attendance.mark', $this->sessionType),
            'vibrate' => [200, 100, 200],
        ];
    }

    protected function getMessage()
    {
        return $this->isFirstReminder
            ? "Please mark {$this->sessionType} attendance"
            : "You haven't marked {$this->sessionType} attendance";
    }

    protected function getSoundFile()
    {
        return $this->isFirstReminder
            ? "{$this->sessionType}_initial.mp3"
            : "{$this->sessionType}_reminder.mp3";
    }
}
