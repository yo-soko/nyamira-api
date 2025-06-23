<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Term;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class TdashboardController extends Controller
{
       public function index()
    {
        $user = Auth::user();

        // Get the class assigned to this teacher
        $class = SchoolClass::with(['level', 'stream'])->where('class_teacher', auth()->id())->first();

        if (!$class) {
            return redirect()->back()->with('error', 'No class assigned to you.');
        }

        // Students in the class
        $students = Student::where('class_id', $class->id)->get();

        // Attendance data
        $todayAttendance = Attendance::whereDate('date', now())
            ->whereIn('student_id', $students->pluck('id'))
            ->count();

        $attendanceStats = [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'data' => collect(range(0, 6))->map(function ($day) use ($students) {
                $date = now()->startOfWeek()->addDays($day);
                return Attendance::whereDate('date', $date)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->count();
            })
        ];

        // Pending exams (e.g., results not submitted)
        $pendingExamsCount = Exam::where('status', '1')
          
            ->count();

        $currentTerm = Term::where('status', 1)->latest('id')->first();

        $currentTermSession = $currentTerm
            ? "{$currentTerm->term_name} / {$currentTerm->year}"
            : 'No active term';

        // Recent activity (stubbed for now)
        $recentActivities = [
            'Student John missed class today.',
            'Jane submitted assignment late.',
            'Guardian of Peter sent a message.'
        ];

   
        // Upcoming exams/events (stubbed)
        $upcomingEvents = Exam::where('status', '1')
            ->orderBy('created_at')
            ->take(5)
            ->pluck('name');

        return view('tdashboard', compact(
            'class',
            'students',
            'todayAttendance',
            'attendanceStats',
            'pendingExamsCount',
            'currentTermSession',
            'recentActivities',
            'upcomingEvents'
        ));
    }
}
