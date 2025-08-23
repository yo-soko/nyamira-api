<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendancies;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\leaves;
use App\Models\Shift;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Term;
use App\Models\Exam;
use App\Models\ClassLevel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Result;
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

         $totalStudents = Student::count();
         $totalTeachers = Teacher::count(); 
         $activeStudents = Student::where('status', 1)->count(); 
         $inactiveStudents = Student::where('status', 0)->count(); 
         $totalUsers = User::count(); 
         $totalLevels = ClassLevel::count(); 
         $totalStreams = SchoolClass::count(); 
         $totalSubjects = Subject::count();
        $currentTerm = Term::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('status', 1)
            ->first();

        // If none found, fallback to latest by end_date
        if (!$currentTerm) {
            $currentTerm = Term::orderBy('end_date', 'desc')->first();
        }
        $examCount = $currentTerm
            ? Exam::where('term_id', $currentTerm->id)->count()
            : 0;
        $previousTerm = Term::where('end_date', '<', $currentTerm->start_date ?? now())
                                ->orderBy('end_date', 'desc')
                                ->first();
        $previousExamCount = $previousTerm
            ? Exam::where('term_id', $previousTerm->id)->count()
            : 0;
       
        // Difference
        $examDiff = $examCount - $previousExamCount;

        $topClass = Result::with('student.class.level', 'term')
            ->where('term_id', $currentTerm->id)
            ->whereNotNull('marks')
            ->get()
            ->filter(fn($r) => $r->student !== null && $r->student->class !== null) // filter out nulls
            ->groupBy(fn($r) => $r->student->class->id) // now safe
            ->map(function ($group) {
                return [
                    'class' => $group->first()->student->class,
                    'average' => $group->filter(fn($r) => $r->marks !== null)->avg('marks')
                ];
            })
            ->sortByDesc('average')
            ->first();


        $topStudent = Result::with('student')
            ->where('term_id', $currentTerm->id)
            ->whereNotNull('marks')
            ->get()
            ->filter(fn($r) => $r->student !== null) // prevent nulls early
            ->groupBy('student_id')
            ->map(function ($results) {
                $student = optional($results->first())->student;

                return [
                    'student' => $student,
                    'average' => $results->avg('marks'),
                ];
            })
            ->sortByDesc('average')
            ->first();

        $topLevel = Result::with('student.class.level')
            ->where('term_id', $currentTerm->id)
            ->whereNotNull('marks')
            ->get()
            ->filter(fn($r) =>
                $r->student !== null &&
                $r->student->class !== null &&
                $r->student->class->level !== null
            )
            ->groupBy(fn($r) => $r->student->class->level->id)
            ->map(function ($group) {
                return [
                    'level' => $group->first()->student->class->level,
                    'average' => $group->avg('marks'),
                ];
            })
            ->sortByDesc('average')
            ->first();


        return view('index', compact(
            'totalLevels', 
            'totalStreams', 
            'totalSubjects',
             'currentTerm',
              'totalStudents',
              'totalTeachers',
              'activeStudents',
              'inactiveStudents',
              'totalUsers',
              'examCount',
              'examDiff',
              'topClass',
              'topStudent',
              'topLevel',
             
            ));
    }
}
