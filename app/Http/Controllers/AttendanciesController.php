<?php

namespace App\Http\Controllers;

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
        // Fetch all employees
        if (session('user_type') == 'employee') {
            $employees = Employee::where('id', session('user_id'))->get(); // Only this employee
        } 
        else if (session('user_type') == 'user') {
            $employees = Employee::all(); // All employees
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
        $employees = Employee::all(); // If you have a large dataset, you can use pagination instead
        
        return view('index', compact('employees', 'greeting', 'totalEmployees', 'activeEmployees', 'inactiveEmployees', 'newJoiners'));
    }

    public function markAttendance($id)
    {
        $hour = Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }

        $employee = Employee::findOrFail($id);

        $today = now()->toDateString();


        // Check if the employee is on break
        $attendance = Attendancies::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();
            
        $alreadyClockedIn = $attendance && $attendance->clock_in;

        $onBreak = $attendance && $attendance->break_start && !$attendance->break_end;
         // Automatically clock-out if grace period has elapsed
        if ($alreadyClockedIn && !$attendance->clock_out) {
            $shift = Shift::find($attendance->shift_id);  // Assuming shift_id exists in Attendancies
            $shiftEndTime = Carbon::parse($shift->end_time);
            
            $gracePeriodEndTime = $shiftEndTime->addMinutes(120); // Assuming a 15-minute grace period

            if (Carbon::now()->gt($gracePeriodEndTime)) {
                // Automatically clock-out after grace period
                $attendance->update([
                    'clock_out' => $shiftEndTime->toTimeString(),
                    'total_hours' => $shiftEndTime->diff($attendance->clock_in)->format('%H:%I:%S'),
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
                    $shiftStartTimeWithGrace = Carbon::parse($shift->start_time)->addHours(2); // 2-hour grace
        
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
            $start = $today->copy()->subMonth()->startOfMonth();
            $end = $today->copy()->subMonth()->endOfMonth();
        } else {
            $start = $today->copy()->startOfMonth();
            $end = $today->copy()->endOfMonth();
        }

        $attendanceRecords = Attendancies::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->latest('date')
            ->get();



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
            return back()->with('error', 'Employee or assigned shift not found.');
        }
        
        // Find the shift based on the shift_id from the employee's shift field
        $shift = Shift::find($employee->shift_id);  // Accessing the shift using the shift field directly
        if (!$shift) {
            return back()->with('error', 'Shift details not found.');
        }

        // Define grace periods (in minutes)
        $graceBefore = 30; // e.g., 30 mins before shift start
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
            return back()->with('error', 'Clock-in not allowed outside shift grace period.');
        }
        
        // Prevent duplicate clock-in for the same shift window
        $alreadyClocked = Attendancies::where('employee_id', $employeeId)
            ->where('shift_id', $shift->id)
            ->whereBetween('clock_in', [$startWindow, $endWindow])
            ->exists();

        if ($alreadyClocked) {
            return back()->with('error', 'Already clocked in for this shift.');
        }

        // Store clock-in record
        Attendancies::create([
            'employee_id' => $employeeId,
            'shift_id' => $shift->id,
            'status' => 'Present',
            'clock_in' => $now,
            'date' => $now->toDateString(),
        ]);

        return back()->with('success', 'Clock In recorded successfully!');
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
            return back()->with('error', 'Employee must be clocked in to take a break.');
        }

        // Mark employee as on break
        $attendance->break_start = now();  // Save the break start time
        $attendance->save();

        return back()->with('success', 'Its nice to take a break!');
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
            return back()->with('error', 'Employee is not on break.');
        }

        // Mark employee as back from break
        $attendance->break_end = now();  // Record the break end time
        $attendance->save();

        return back()->with('success', 'Welcome Back from break!');
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
            return back()->with('error', 'Employee already clocked out or not clocked in for the shift.');
        }
    
        // Check if the attendance has a valid shift relationship
        if (!$attendance->shift) {
            return back()->with('error', 'No shift associated with this attendance.');
        }
    
        // Record clock-out time
        $clockOutTime = now();
        $attendance->clock_out = $clockOutTime;
    
        // Ensure clock-in time exists
        $clockInTime = Carbon::parse($attendance->clock_in);
        if (!$clockInTime) {
            return back()->with('error', 'Invalid clock-in time.');
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
                return back()->with('error', 'Invalid break time: Break start cannot be later than break end.');
            }
    
            $breakDuration = $breakEnd->diffInSeconds($breakStart);
        }
    
        // Subtract break duration from total work duration
        $workDuration -= $breakDuration;
    
        // Format the total work time (H:i:s)
        $attendance->total_hours = gmdate("H:i:s", $workDuration);
    
        // Save the updated attendance record
        $attendance->save();
    
        return back()->with('success', 'Clock Out time recorded successfully!');
    }   
}
