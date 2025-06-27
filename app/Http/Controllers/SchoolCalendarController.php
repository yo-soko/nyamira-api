<?php

namespace App\Http\Controllers;

use App\Models\SchoolCalendar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SchoolCalendarController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year from request or use current
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $currentDate = Carbon::create($year, $month, 1);

        // Get events for the current month view
        $events = SchoolCalendar::whereMonth('event_date', $month)
            ->whereYear('event_date', $year)
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        // Get upcoming events (next 30 days)
        $upcomingEvents = SchoolCalendar::where('event_date', '>=', now())
            ->where('event_date', '<=', now()->addDays(30))
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        // Prepare months and years for dropdowns
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create()->month($m)->format('F');
        }

        $years = range(now()->year - 2, now()->year + 5);

        return view('calendar', compact(
            'currentDate',
            'events',
            'upcomingEvents',
            'months',
            'years'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'event_location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_holiday' => 'nullable|boolean',
            'event_color' => 'required|string|max:7'
        ]);

        $event = SchoolCalendar::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'event' => $event
        ]);
    }

    public function updateEvent(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:school_calendars,id',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'event_location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_holiday' => 'nullable|boolean',
            'event_color' => 'required|string|max:7'
        ]);

        $event = SchoolCalendar::findOrFail($request->id);
        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    public function show($id)
    {
        $event = SchoolCalendar::findOrFail($id);

        return response()->json($event);
    }


    public function destroy($id)
    {
        $event = SchoolCalendar::findOrFail($id);
        $event->delete();

        return response()->json(['success' => true]);
    }

    public function updateEventDate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:school_calendars,id',
            'event_date' => 'required|date'
        ]);

        $event = SchoolCalendar::find($request->id);
        $event->event_date = $request->event_date;
        $event->save();

        return response()->json(['success' => true]);
    }

    public function getCalendarData(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $view = $request->input('view', 'month');

        $currentDate = Carbon::create($year, $month, 1);

        // Get events for the month
        $events = SchoolCalendar::whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        // Get upcoming events (next 30 days)
        $upcomingEvents = SchoolCalendar::where('event_date', '>=', now())
            ->where('event_date', '<=', now()->addDays(30))
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'currentYear' => $currentDate->year,
            'currentMonth' => $currentDate->month - 1, // JavaScript uses 0-indexed months
            'events' => $events,
            'upcomingEvents' => $upcomingEvents,
            'view' => $view
        ]);
    }
}
