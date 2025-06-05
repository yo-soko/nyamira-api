<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Attendancies;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Shift;
use Carbon\Carbon;

class AttendanciesController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 1)->count(); // assuming 1 = active
        $inactiveEmployees = Employee::where('status', 0)->count(); // assuming 0 = inactive
        $newJoiners = Employee::where('joining_date', '>=', Carbon::now()->subDays(30))->count();
      
        if (auth()->user()->hasRole('employee')) {
            $employees = Employee::where('user_id', auth()->id())->get();
        } 
        elseif (auth()->user()->hasAnyRole(['admin', 'superadmin', 'director', 'developer', 'manager', 'supervisor'])) {
            $employees = Employee::latest()->get();
        }
        else{
            $employees = collect();
        }
        return view('attendance-admin', compact('employees','totalEmployees', 'activeEmployees', 'inactiveEmployees', 'newJoiners'));
    }

    public function indexx()
    {
        $hour = Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 1)->count(); // assuming 1 = active
        $inactiveEmployees = Employee::where('status', 0)->count(); // assuming 0 = inactive
        $newJoiners = Employee::where('joining_date', '>=', Carbon::now()->subDays(30))->count();
        // Fetch all employees
        $employees = Employee::with(['department', 'designation'])->get();
         // If you have a large dataset, you can use pagination instead
        
        return view('index', compact('employees', 'greeting', 'totalEmployees', 'activeEmployees', 'inactiveEmployees', 'newJoiners'));
    }

    public function markAttendance(Request $request)
    {
        $employeeId = $request->input('employee_id') ?? session('employee_id');

        $employee = Employee::findOrFail($employeeId);

        $hour = Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }


        $today = now()->toDateString();


        // Check if the employee is on break
        $attendance = Attendancies::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();
            
        $alreadyClockedIn = $attendance && $attendance->clock_in;

        $onBreak = $attendance && $attendance->break_start && !$attendance->break_end;
         // Automatically clock-out if grace period has elapsed
        if ($alreadyClockedIn && !$attendance->clock_out) {
            $shift = Shift::find($attendance->shift_id);
            
            $shiftStart = Carbon::today()->setTimeFromTimeString($shift->start_time);
            $shiftEnd = Carbon::today()->setTimeFromTimeString($shift->end_time);

            if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
                $shiftEnd->addDay();
            }

            $gracePeriodEndTime = $shiftEnd->copy()->addMinutes(120); // 2 hour grace

            if (Carbon::now()->gt($gracePeriodEndTime)) {
                $attendance->update([
                    'clock_out' => $shiftEnd->toDateTimeString(),
                    'total_hours' => $shiftEnd->diff($attendance->clock_in)->format('%H:%I:%S'),
                ]);
            }
        }


        $currentMonth = now()->month;
        $currentYear   = now()->year;

        $previousMonth = Carbon::now()->subMonth()->format('F Y');
        // 1. Determine the previous month window
        $start = Carbon::now()->subMonth()->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // 2. Calendar-based total days & Sundays
        $totalDays = $start->daysInMonth;
        $sundays   = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isSunday()) {
                $sundays++;
            }
        }
        $totalWorkingDays = $totalDays - $sundays;
    
        // 2. Count days present (at least one clock-in record)
        $presentDays = Attendancies::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->whereNotNull('clock_in')
            ->distinct('date')
            ->count('date');

        // 3. Absent = working days – present days
        $absentDays = $totalWorkingDays - $presentDays;
        //4 late days
    
        $lateDays = 0;

        $attendanceRecords = Attendancies::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->get();
        
        foreach ($attendanceRecords as $record) {
            if ($record->clock_in && $record->shift_id) {
                $shift = Shift::find($record->shift_id);
        
                if ($shift && $shift->start_time) {
                    $clockInTime = Carbon::parse($record->clock_in);
                    $shiftStartTimeWithGrace = Carbon::parse($shift->start_time)->addHours(1); // 1-hour grace
        
                    if ($clockInTime->gt($shiftStartTimeWithGrace)) {
                        $lateDays++;
                    }
                }
            }
        }
        
        // Fetch total half days in the current month
        $halfDays = Attendancies::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->whereRaw('TIME_TO_SEC(total_hours) < ?', [4*3600])
            ->distinct('date')
            ->count('date');
    

            $period = new \DatePeriod(
            $start,
            new \DateInterval('P1D'),
            $end->copy()->addDay()
        );
    
        $holidayDays = 0;
    
        foreach ($period as $day) {
            // wrap in Carbon
            $date = Carbon::instance($day)->toDateString();
    
            if (Holiday::where('from_date', '<=', $date)
                        ->where('to_date', '>=',  $date)
                        ->exists()) {
                $holidayDays++;
            }
        }
        $today = Carbon::today();

        if ($today->day <= 5) {
            // Combine: previous month + current month up to today
            $previousStart = $today->copy()->subMonth()->startOfMonth();
            $currentEnd = $today->copy(); // today is up to date 5

            $attendanceRecords = Attendancies::where('employee_id', $employee->id)
                ->where(function ($query) use ($previousStart, $currentEnd) {
                    $query->whereBetween('date', [$previousStart, $currentEnd]);
                })
                ->latest('date')
                ->get();
        } else {
            // Current month only
            $currentStart = $today->copy()->startOfMonth();
            $currentEnd = $today->copy()->endOfMonth();

            $attendanceRecords = Attendancies::where('employee_id', $employee->id)
                ->whereBetween('date', [$currentStart, $currentEnd])
                ->latest('date')
                ->get();
        }




        return view('attendance-employee', compact('employee', 'greeting', 'alreadyClockedIn',
        'onBreak','attendance','totalWorkingDays', 'absentDays', 'presentDays', 'halfDays', 
        'holidayDays','previousMonth','attendanceRecords','lateDays'));
    }

    public function clockIn(Request $request)
    {
        $employeeId = $request->input('employee_id');

        if (!$employeeId) {
            return back()->with('error', 'Employee ID is required.');
        }

        // Get the employee by ID
        $employee = Employee::find($employeeId);
        if (!$employee || !$employee->shift_id) {  // Using employee's shift field
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Employee or assigned shift not found.');
            return redirect()->route('attendance.mark');
        }
        
        // Find the shift based on the shift_id from the employee's shift field
        $shift = Shift::find($employee->shift_id);  // Accessing the shift using the shift field directly
        if (!$shift) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Shift details not found.');
            return redirect()->route('attendance.mark');
        }

        // Define grace periods (in minutes)
        $graceBefore = 90; // e.g., 30 mins before shift start
        $graceAfter = 450;  // e.g., 450 mins after shift start

        // Build shift window
        $now = now();

        // Step 1: Set shift start and end on today’s date
        $today = Carbon::today();
        $shiftStart = Carbon::parse($today->toDateString() . ' ' . $shift->start_time);
        $shiftEnd = Carbon::parse($today->toDateString() . ' ' . $shift->end_time);
        
        // Step 2: Handle overnight shift (e.g., 10PM to 6AM)
        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay(); // end time is tomorrow
        }
        
        // Step 3: Calculate grace window (start 30 mins before, end 7.5 hrs after shift start)
        $startWindow = $shiftStart->copy()->subMinutes($graceBefore);
        $endWindow = $shiftStart->copy()->addMinutes($graceAfter);
        
        // Step 4: Check if now is within clock-in window
        if (!$now->between($startWindow, $endWindow)) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Clock-in not allowed outside shift grace period.');
            return redirect()->route('attendance.mark');
        }
        
        // Prevent duplicate clock-in for the same shift window
        $alreadyClocked = Attendancies::where('employee_id', $employeeId)
            ->where('shift_id', $shift->id)
            ->whereBetween('clock_in', [$startWindow, $endWindow])
            ->exists();

        if ($alreadyClocked) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Already clocked in for this shift.');
            return redirect()->route('attendance.mark');
        }

        // Store clock-in record
        Attendancies::create([
            'employee_id' => $employeeId,
            'shift_id' => $shift->id,
            'status' => 'Present',
            'clock_in' => $now,
            'date' => $now->toDateString(),
        ]);

        session()->put('employee_id', $employeeId);
        session()->flash('success', 'Clock-in successful!');
        return redirect()->route('attendance.mark');

    }

    public function break(Request $request)
    {
        $employeeId = $request->input('employee_id');

        if (!$employeeId) {
            return back()->with('error', 'Employee ID is required.');
        }

        $attendance = Attendancies::where('employee_id', $employeeId)
            ->whereDate('date', now()->toDateString())
            ->whereNull('clock_out')  // Ensure the employee is clocked in and not clocked out yet
            ->first();

        if (!$attendance) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Employee must be clocked in to take a break.');
            return redirect()->route('attendance.mark');
        }

        // Mark employee as on break
        $attendance->break_start = now();  // Save the break start time
        $attendance->save();

        session()->put('employee_id', $employeeId);
        session()->flash('success', 'Its nice to take a break!');
        return redirect()->route('attendance.mark');
       
    }

    public function backFromBreak(Request $request)
    {
        $employeeId = $request->input('employee_id');

        if (!$employeeId) {
            return back()->with('error', 'Employee ID is required.');
        }

        $attendance = Attendancies::where('employee_id', $employeeId)
            ->whereDate('date', now()->toDateString())
            ->whereNull('clock_out')  // Ensure the employee is clocked in and not clocked out yet
            ->whereNotNull('break_start')  // Ensure the employee is currently on break
            ->first();

        if (!$attendance) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Employee is not on break.');
            return redirect()->route('attendance.mark');
        }

        // Mark employee as back from break
        $attendance->break_end = now();  // Record the break end time
        $attendance->save();

        session()->put('employee_id', $employeeId);
        session()->flash('success', 'Welcome Back from break!');
        return redirect()->route('attendance.mark');
    }

    public function clockOut(Request $request)
    {
        $employeeId = $request->input('employee_id');
    
        if (!$employeeId) {
            return back()->with('error', 'Employee ID is required.');
        }
    
        // Retrieve attendance record for today
        $attendance = Attendancies::where('employee_id', $employeeId)
            ->whereDate('date', now()->toDateString())
            ->whereNull('clock_out')  // Ensure the employee is not already clocked out
            ->first();
    
        if (!$attendance) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Employee already clocked out or not clocked in for the shift.');
            return redirect()->route('attendance.mark');
        }
    
        // Check if the attendance has a valid shift relationship
        if (!$attendance->shift) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'No shift associated with this attendance.');
            return redirect()->route('attendance.mark');
        }
    
        // Record clock-out time
        $clockOutTime = now();
        $attendance->clock_out = $clockOutTime;
    
        // Ensure clock-in time exists
        $clockInTime = Carbon::parse($attendance->clock_in);
        if (!$clockInTime) {
            session()->put('employee_id', $employeeId);
            session()->flash('error', 'Invalid clock-in time.');
            return redirect()->route('attendance.mark');
        }
    
        // Handle case for shifts spanning over midnight
        $shiftStart = Carbon::parse($attendance->shift->start_time);
        $shiftEnd = Carbon::parse($attendance->shift->end_time);
    
        // If the shift crosses midnight (e.g., 10:00 PM to 6:00 AM), adjust the clock-out time accordingly
        if ($shiftEnd->lessThan($shiftStart)) {
            // Shift ends after midnight, adjust to the next day
            $shiftEnd->addDay();
        }
    
        // Calculate total work duration in seconds
        if ($clockOutTime->lt($shiftStart)) {
            $clockOutTime->addDay(); // Add a day to the clock-out time if it's before the shift start
        }
    
        // Calculate the work duration (in seconds)
        $workDuration = $clockInTime->diffInSeconds($clockOutTime);
    
        // Calculate break duration if applicable
        $breakDuration = 0; // Default break duration if not applicable
        $breakStart = $attendance->break_start ? Carbon::parse($attendance->break_start) : null;
        $breakEnd = $attendance->break_end ? Carbon::parse($attendance->break_end) : null;
    
        if ($breakStart && $breakEnd) {
            if ($breakStart->greaterThan($breakEnd)) {
                session()->put('employee_id', $employeeId);
                session()->flash('error', 'Invalid break time: Break start cannot be later than break end.');
                return redirect()->route('attendance.mark');
            }
    
            $breakDuration = $breakEnd->diffInSeconds($breakStart);
        }
    
        // Subtract break duration from total work duration
        $workDuration -= $breakDuration;
    
        // Format the total work time (H:i:s)
        $attendance->total_hours = gmdate("H:i:s", $workDuration);
    
        // Save the updated attendance record
        $attendance->save();
    
        session()->put('employee_id', $employeeId);
        session()->flash('success', 'Clock Out time recorded successfully!');
        return redirect()->route('attendance.mark');
    }   


    public function autoClockOutForgottenEmployees()
    {
        // Get all attendance records where employee forgot to clock out
        // and the attendance date is before today
        $attendances = Attendancies::whereNull('clock_out')
            ->whereDate('date', '<', now()->toDateString())
            ->get();

        foreach ($attendances as $attendance) {
            try {
                // Skip if there's no shift info
                if (!$attendance->shift) {
                    Log::warning("No shift found for attendance ID: {$attendance->id}");
                    continue;
                }

                // Get shift end and subtract 5 hours
                $shiftEnd = Carbon::parse($attendance->shift->end_time);
                $shiftStart = Carbon::parse($attendance->shift->start_time);

                // Handle overnight shift (e.g., 10 PM - 6 AM)
                if ($shiftEnd->lessThan($shiftStart)) {
                    $shiftEnd->addDay();
                }

                // Set clock_out as shift_end - 5 hours
                $clockOutTime = $shiftEnd->copy()->subHours(5);

                // Parse clock-in time
                $clockInTime = Carbon::parse($attendance->clock_in);

                // Calculate work duration in seconds
                $workDuration = $clockInTime->diffInSeconds($clockOutTime);

                // Subtract break duration if available
                $breakDuration = 0;
                if ($attendance->break_start && $attendance->break_end) {
                    $breakStart = Carbon::parse($attendance->break_start);
                    $breakEnd = Carbon::parse($attendance->break_end);
                    if ($breakEnd->greaterThan($breakStart)) {
                        $breakDuration = $breakEnd->diffInSeconds($breakStart);
                    }
                }

                $workDuration -= $breakDuration;

                // Final updates
                $attendance->clock_out = $clockOutTime;
                $attendance->total_hours = gmdate("H:i:s", $workDuration);
                $attendance->save();

                Log::info("Auto clocked-out employee ID {$attendance->employee_id} with 5hr adjusted time on attendance ID {$attendance->id}");

            } catch (\Exception $e) {
                Log::error("Failed to auto clock out attendance ID {$attendance->id}: " . $e->getMessage());
            }
        }

       return redirect()->intended('index')->with('success', 'clock out recorded for every employee!');
    }

}
