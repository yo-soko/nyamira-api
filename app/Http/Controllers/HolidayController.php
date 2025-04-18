<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use Carbon\Carbon;



class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::latest()->get();
        return view('holidays', compact('holidays'));
    }

    public function store(Request $request)
    {
        // Convert first before validation
        $request->merge([
            'from_date' => Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d'),
            'to_date' => Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d'),
            'status' => $request->has('status') ? 1 : 0,
        ]);

        // Now validate
        $validated = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days_count' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        // Calculate days_count if not provided
        if (!$request->filled('days_count')) {
            $validated['days_count'] = Carbon::parse($validated['from_date'])
                ->diffInDays(Carbon::parse($validated['to_date'])) + 1;
        }


        Holiday::create($validated);

        return redirect()->back()->with('success', 'Holiday added successfully!');
    }

    public function update(Request $request)
    {
        // Convert dates first
        $request->merge([
            'from_date' => Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d'),
            'to_date' => Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d'),
            'status' => $request->has('status') ? 1 : 0,
        ]);
    
        // Validate the request
        $validated = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days_count' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
    
        // Calculate days_count if not manually provided
        if (!$request->filled('days_count')) {
            $validated['days_count'] = Carbon::parse($validated['from_date'])
                ->diffInDays(Carbon::parse($validated['to_date'])) + 1;
        }
    
        
    
        // Find the existing holiday record and update
        $holiday = Holiday::findOrFail($request->id);
        $holiday->update($validated);
    
        return redirect()->back()->with('success', 'Holiday updated successfully!');
    }
    
    // Delete a holiday
    public function destroy(Request $request)
    {
        $holiday = Holiday::findOrFail($request->id);
        $holiday->delete();

        return redirect()->back()->with('success', 'Holiday deleted successfully!');
    }
}
