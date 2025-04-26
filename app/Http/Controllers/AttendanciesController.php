<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendancies;
use App\Models\Employee;
use App\Models\Holiday;
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
        $employees = Employee::all(); // If you have a large dataset, you can use pagination instead
        
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

        $alreadyClockedIn = Attendancies::where('employee_id', $employee->id)
            ->whereDate('clock_in', $today)
            ->exists();
        // Check if the employee is on break
        $attendance = Attendancies::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        $alreadyClockedIn = $attendance && $attendance->clock_in;
        $onBreak = $attendance && $attendance->break_start && !$attendance->break_end;
        
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
        $attendanceRecords = Attendancies::where('employee_id', $employee->id)->get();

        return view('attendance-employee', compact('employee', 'greeting', 'alreadyClockedIn','onBreak','attendance','totalWorkingDays', 'absentDays', 'presentDays', 'halfDays', 'holidayDays','previousMonth','attendanceRecords'));
    }

    public function clockIn(Request $request)
    {
        $employeeId = $request->input('employee_id');

        if (!$employeeId) {
            return back()->with('error', 'Employee ID is required.');
        }

        $today = now()->toDateString();

        // Prevent multiple clock-ins in one day
        $alreadyClocked = Attendancies::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->exists();

        if ($alreadyClocked) {
            return back()->with('error', 'Already clocked in today.');
        }

        Attendancies::create([
            'employee_id' => $employeeId,
            'status' => 'Present',
            'clock_in' => now(),
            'date' => $today,
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
    
        $attendance = Attendancies::where('employee_id', $employeeId)
            ->whereDate('date', now()->toDateString())
            ->whereNull('clock_out')
            ->first();
    
        if (!$attendance) {
            return back()->with('error', 'Employee already clocked out or not clocked in today.');
        }
    
        $clockOutTime = now(); // <- use a variable
    
        $attendance->clock_out = $clockOutTime;
    
        $clockInTime = Carbon::parse($attendance->clock_in);
    
        // Total duration in seconds
        $workDuration = $clockInTime->diffInSeconds($clockOutTime); 

    
        // Subtract break time if applicable
        $breakStart = $attendance->break_start ? Carbon::parse($attendance->break_start) : null;
        $breakEnd = $attendance->break_end ? Carbon::parse($attendance->break_end) : null;
    
        if ($breakStart && $breakEnd) {
            $breakDuration = $breakEnd->diffInSeconds($breakStart);
            $workDuration -= $breakDuration;
        }
    
        
    
        // Format and save total hours
        $attendance->total_hours = gmdate("H:i:s", $workDuration);        
        $attendance->save();
    
        return back()->with('success', 'Clock Out time recorded successfully!');
    }
    
    
    
}
