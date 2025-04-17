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
    
        // Remove AM/PM if time has it
        $time = preg_replace('/\s?(AM|PM)/i', '', $time);
    
        try {
            return Carbon::createFromFormat('h:i A', $time)->format('H:i:s');
        } catch (\Exception $e1) {
            try {
                return Carbon::createFromFormat('H:i', $time)->format('H:i:s');
            } catch (\Exception $e2) {
                try {
                    return Carbon::createFromFormat('H:i:s', $time)->format('H:i:s'); // <-- add this
                } catch (\Exception $e3) {
                    \Log::error("Time parsing failed for input: '$time'");
                    return null;
                }
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
            'morning_from' => $this->parseTime($request->morning_from),
            'morning_to' => $this->parseTime($request->morning_to),
            'lunch_from' => $this->parseTime($request->lunch_from) ,
            'lunch_to' => $this->parseTime($request->lunch_to),
            'evening_from' => $this->parseTime($request->evening_from),
            'evening_to' => $this->parseTime($request->evening_to),

            'description' => $request->description,
        ]);
    
        return redirect()->back()->with('success', 'Shift added successfully!');
    }
    public function update(Request $request)
    {
        $shift = Shift::findOrFail($request->id);
    
        $data = $request->all();
    
        $validated = $request->validate([
            'shift_name' => 'sometimes|required|string',
            'start_time' => 'sometimes|required',
            'end_time' => 'sometimes|required',
            'day_off' => 'nullable|string',
            'days' => 'sometimes|required|array',
            'morning_from' => 'nullable',
            'morning_to' => 'nullable',
            'lunch_from' => 'nullable',
            'lunch_to' => 'nullable',
            'evening_from' => 'nullable',
            'evening_to' => 'nullable',
            'description' => 'nullable|string',
        ]);

        if ($request->has('shift_name')) $shift->shift_name = $request->shift_name;
        if ($request->has('start_time')) $shift->start_time = $this->parseTime($request->start_time);
        if ($request->has('end_time')) $shift->end_time = $this->parseTime($request->end_time);
        if ($request->has('days')) $shift->days = implode(',', $request->days);
        if ($request->has('day_off')) $shift->day_off = $request->day_off;

        $shift->recurring = $request->has('recurring');
        $shift->status = $request->has('status');

        // Optional fields - wrap in null-checking parseTime
        $shift->morning_from = $request->morning_from ? $this->parseTime($request->morning_from) : null;
        $shift->morning_to = $request->morning_to ? $this->parseTime($request->morning_to) : null;
        $shift->lunch_from = $request->lunch_from ? $this->parseTime($request->lunch_from) : null;
        $shift->lunch_to = $request->lunch_to ? $this->parseTime($request->lunch_to) : null;
        $shift->evening_from = $request->evening_from ? $this->parseTime($request->evening_from) : null;
        $shift->evening_to = $request->evening_to ? $this->parseTime($request->evening_to) : null;
        $shift->description = $request->description;


        $shift->save();
    
        return redirect()->route('shift')->with('success', 'Shift updated successfully!');
    }
    

    public function destroy(Request $request)
    {
        $shift = Shift::findOrFail($request->id);
        $shift->delete();
    
        return redirect()->back()->with('success', 'Shift deleted successfully!');
    }
}
