<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendancies;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaves;
use App\Models\Shift;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hour = Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }
        $today = Carbon::today();

        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 1)->count(); // assuming 1 = active
        $inactiveEmployees = Employee::where('status', 0)->count(); // assuming 0 = inactive
        $presentToday = Attendancies::whereDate('date', $today)->distinct('employee_id')->count('employee_id');
        $newJoiners = Employee::where('joining_date', '>=', Carbon::now()->subDays(30))->count();

        $onLeave = leaves::whereDate('from_date', '<=', $today)
            ->whereDate('to_date', '>=', $today)
            ->count();

        
        $graceMinutes = 30;

        $lateCount = 0;

        // Get today's attendances with clock_in
        $attendances = Attendancies::whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->get();

        // Preload all shifts to avoid N+1 queries
        $shifts = Shift::all()->keyBy('id');

        $checkedEmployees = [];

        foreach ($attendances as $att) {
            $shift = $shifts[$att->shift_id] ?? null;

            if (!$shift) continue;

            $clockIn = Carbon::parse($att->clock_in);
            $shiftStart = Carbon::parse($today->toDateString() . ' ' . $shift->start_time)
                            ->addMinutes($graceMinutes);

            if ($clockIn->gt($shiftStart) && !in_array($att->employee_id, $checkedEmployees)) {
                $lateCount++;
                $checkedEmployees[] = $att->employee_id;
            }
            $lateCount++;
        }

        $Inn = Attendancies::with('employee') // assuming there's a relationship
            ->whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->get();

        $In = $Inn->count();
        // Fetch all employees
        $employees = Employee::with(['department', 'designation'])->get();
         // If you have a large dataset, you can use pagination instead
         $totalStudents = Student::count();
         $totalTeachers = Teacher::count(); 
         $activeStudents = Student::where('status', 1)->count(); 
         $inactiveStudents = Student::where('status', 0)->count(); 
         $totalUsers = User::count(); 
       
        return view('index', compact(
            'employees','onLeave','presentToday', 'lateCount','In', 
            'greeting', 'totalEmployees', 'activeEmployees', 'inactiveEmployees',
             'newJoiners',
              'totalStudents',
              'totalTeachers',
              'activeStudents',
              'inactiveStudents',
              'totalUsers',
             
            ));
    }
}
