<?php

namespace App\Http\Controllers;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftController extends Controller
{
    private function parseTime($time)
    {
        $time = trim($time);
    
        // Remove AM/PM if time is already in 24-hour format
        if (preg_match('/^[0-2][0-9]:[0-5][0-9]/', $time) && (str_contains($time, 'AM') || str_contains($time, 'PM'))) {
            $time = preg_replace('/\s?(AM|PM)/i', '', $time);
        }
    
        try {
            // Try parsing 12-hour time with AM/PM
            return Carbon::createFromFormat('h:i A', $time)->format('H:i:s');
        } catch (\Exception $e1) {
            try {
                // Try 24-hour time format
                return Carbon::createFromFormat('H:i', $time)->format('H:i:s');
            } catch (\Exception $e2) {
                \Log::error("Time parsing failed for input: '$time'");
                return null;
            }
        }
    }
    

    public function index()
    {
        $shift =    Shift::all(); // Or paginate() if you prefer
        return view('shift', compact('shift'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'day_off' => 'nullable|string',
            'days' => 'required|array',
            'morning_from' => 'nullable',
            'morning_to' => 'nullable',
            'lunch_from' => 'nullable',
            'lunch_to' => 'nullable',
            'evening_from' => 'nullable',
            'evening_to' => 'nullable',
            'description' => 'nullable|string',
        ]);

        Shift::create([
            'shift_name' => $request->shift_name,
            'start_time' => $this->parseTime($request->start_time),
            'end_time' => $this->parseTime($request->end_time),
            'day_off' => $request->day_off,
            'days' => implode(',', $request->days),
            'recurring' => $request->has('recurring'),
            'status' => $request->has('status'),
            'morning_from' =>$this->parseTime($request->morning_from),
            'morning_to' => $this->parseTime($request->morning_to),
            'lunch_from' =>  $this->parseTime($request->lunch_from),
            'lunch_to' =>  $this->parseTime($request->lunch_to),
            'evening_from' =>  $this->parseTime($request->evening_from),
            'evening_to' =>  $this->parseTime($request->evening_to),
            'description' => $request->description,
        ]);
    
        return redirect()->back()->with('success', 'Shift added successfully!');
    }
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $shift->shift_name = $request->input('shift_name');
        $shift->start_time = $request->input('start_time');
        $shift->end_time = $request->input('end_time');
        $shift->day_off = $request->input('day_off');
        $shift->morning_from = $request->input('morning_from');
        $shift->morning_to = $request->input('morning_to');
        // Continue for lunch, evening break, and any other fields...

        $shift->save();

        return redirect()->route('shift.index')->with('success', 'Shift updated successfully!');
    }

}
