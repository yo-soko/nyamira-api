<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if (in_array($user->role, ['admin', 'developer', 'manager', 'director', 'supervisor'])) {

          $classes = SchoolClass::with('classTeacher')->get();

          return view('attendance-all', compact('classes'));

        }
        elseif ($user->role === 'class_teacher') {
            // Find the class where this user is the class teacher
            $class = SchoolClass::with(['level', 'stream'])->where('class_teacher', $user->id)->first();

            if (!$class) {
                return redirect()->back()->with('error', 'No class assigned to you.');
            }

            // Get students related to this class
            $students = $class->students()->get();
            $today = Carbon::now()->format('l, jS F Y');

            return view('attendance', compact('class', 'students','today'));
       }

       abort(403, 'Unauthorized access');
    }

    public function attendanceAll(Request $request)
    {
        $classId = $request->input('class_id');

        $class = SchoolClass::with(['students', 'classTeacher', 'level', 'stream'])->findOrFail($classId);

        $students = $class->students;
        $today = Carbon::now()->format('l, jS F Y');
        // Pass to view that marks attendance for that class
        return view('attendance', compact('class', 'students','today'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attendances = $request->input('attendance', []);

        if (empty($attendances)) {
            return redirect()->back()->withErrors('No attendance data submitted.');
        }

        // Get the class_id from the first student (assuming all students belong to the same class)
        $firstStudentId = array_key_first($attendances);
        $classId = Student::find($firstStudentId)?->class_id;

        if (!$classId) {
            return redirect()->back()->withErrors('Invalid student or class data.');
        }

        // Get the session from the first attendance record (assuming all attendance are for the same session)
        $firstSession = $attendances[$firstStudentId]['session'] ?? null;

        if (!$firstSession) {
            return redirect()->back()->withErrors('Please select a session.');
        }

        // Check if attendance for this class, date and session already exists
        $existing = Attendance::where('class_id', $classId)
            ->where('date', now()->toDateString())
            ->where('session', $firstSession)
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'Attendance records for this class, session and date already exist.');
        }

        // Validate input for each student attendance record
        $rules = [];
        foreach ($attendances as $studentId => $data) {
            $rules["attendance.$studentId.session"] = 'required|in:morning,afternoon';

            // Require at least one checkbox per student
            $rules["attendance.$studentId.present"] = 'nullable|in:present';
            $rules["attendance.$studentId.absent"] = 'nullable|in:absent';
            $rules["attendance.$studentId.other"] = 'nullable|in:other';

            // If 'other' is checked, require reason text
            if (!empty($data['other'])) {
                $rules["attendance.$studentId.reason"] = 'required|string|max:255';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process and save attendance
        foreach ($attendances as $student_id => $data) {
            // Determine attendance status based on checkbox (same as before)
            $status = null;
            if (isset($data['present'])) {
                $status = 'Present';
            } elseif (isset($data['absent'])) {
                $status = 'Absent';
            } elseif (isset($data['other'])) {
                $status = 'Excused';
            }

            if (!$status) {
                // Skip if no status checked
                continue;
            }

            Attendance::create([
                'student_id' => $student_id,
                'class_id' => $classId,
                'user_id' =>auth()->id(),
                'date' => now()->toDateString(),
                'session' => $data['session'],
                'status' => $status,
                'reason' => $data['reason'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Attendance submitted successfully.');
    }

    public function filter(Request $request)
    {
        $classId = $request->input('class_id');
        $dates = []; 

        $query = Student::where('class_id', $classId);

        // Filter by date or week
        if ($request->filled('date')) {
            $date = $request->date;

            $students = $query->with(['attendance' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }])->get();

        } elseif ($request->filled('week_start') && $request->filled('week_end')) {
            $start = $request->week_start;
            $end = $request->week_end;

            // Build date range
            $dates = [];
            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }

            // Now fetch students with attendance between the dates
            $students = $query->with(['attendance' => function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            }])->get();

        } else {
            $students = $query->with('attendance')->get();
        }

        // Also load the class for the header info
        $class = SchoolClass::find($classId);

        return view('attendance-filter', compact('students', 'class', 'dates'))
        ->with('filters', $request->all());

    }



    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
