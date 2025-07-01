<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\AttendanceReminderNotification;

class NotificationsController extends Controller
{
    public function unread(Request $request)
    {
        return $request->user()->unreadNotifications()
            ->where('type', AttendanceReminderNotification::class)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->diffForHumans()
                ];
            });
    }

    public function markAsRead(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $notification = $request->user()->notifications()
            ->where('id', $request->id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
