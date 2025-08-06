<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Subject;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $examData = $request->input('exams');
        $termId = $request->input('term_id');
        $examId = $request->input('exam_id');
        $classId = $request->input('class_id');

        // ✅ authorization
        $teacher = Auth::user()->teacher;

        if ($teacher) {
            $allowedPairs = $teacher->subjects->map(function($subject) {
                return [
                    'subject_id' => $subject->id,
                    'class_id'   => $subject->pivot->class_id
                ];
            });

            $isAuthorized = $allowedPairs->first(function($pair) use ($request) {
                return $pair['subject_id'] == $request->subject_id &&
                       $pair['class_id']   == $request->class_id;
            });

            if (!$isAuthorized) {
                return back()->with('error', 'You are not authorized to submit results for this subject and class.');
            }
        }

        $existingStudents = [];

        foreach ($examData as $studentId => $data) {
            $isAbsent = isset($data['absent']) && $data['absent'] == 1;

            if (!$isAbsent && empty($data['grade'])) {
                return back()->with("error", "All grades are required unless marked absent.");
            }

            $existingResult = Result::where('student_id', $studentId)
                ->where('exam_id', $examId)
                ->where('class_id', $classId)
                ->where('term_id', $termId)
                ->where('subject_id', $data['subject'])
                ->first();

            if ($existingResult) {
                $existingStudents[] = $studentId;
            }
        }

        if (!empty($existingStudents)) {
            $names = Student::whereIn('id', $existingStudents)
                ->pluck('first_name', 'id')
                ->values()
                ->toArray();

            $namesStr = implode(', ', $names);

            return back()->with("error", "Results for the following students have already been submitted: $namesStr");
        }

        // save new results
        foreach ($examData as $studentId => $data) {
            $isAbsent = isset($data['absent']) && $data['absent'] == 1;

            Result::create([
                'student_id' => $studentId,
                'exam_id' => $examId,
                'class_id' => $classId,
                'subject_id' => $data['subject'],
                'marks' => $isAbsent ? null : ($data['marks'] ?? null),
                'grade' => $isAbsent ? null : $data['grade'],
                'comments' => $data['comments'] ?? null,
                'absent' => $isAbsent,
                'term_id' => $termId,
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Results submitted successfully.');
    }

    public function filterForm()
    {
        $terms = Term::where('status', 1)->get();
        $classes = SchoolClass::with(['stream', 'level'])->where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        $exams = Exam::where('status', 1)->get();

        return view('submit-results', compact('terms', 'classes', 'subjects', 'exams'));
    }

    public function entryForm(Request $request)
    {
        $request->validate([
            'term_id' => 'required|exists:terms,id',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $termId = $request->term_id;
        $classId = $request->class_id;
        $subjectId = $request->subject_id;
        $examId = $request->exam_id;

        $class = SchoolClass::findOrFail($classId);
        $levelId = $class->level_id;

        $exists = DB::table('exam_subjects_classes')
            ->where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->where('level_id', $levelId)
            ->exists();

        if (!$exists) {
            return back()->with('error', 'You cannot enter results: The selected exam and subject are not assigned to this class level.');
        }

        $students = Student::where('class_id', $classId)
                   ->where('status', 1)
                   ->get();

        return view('results_entry', compact('termId', 'classId', 'subjectId', 'examId', 'students'));
    }

    public function showFilterForm()
    {
        return view('results-filter', [
            'levels' => ClassLevel::all(),
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'exams' => Exam::all(),
            'terms' => Term::all()
        ]);
    }

    public function filterResults(Request $request)
    {
        $data = $request->only(['class_id', 'exam_id', 'term_id', 'subject_id', 'level_id']);

        if (empty(array_filter($data))) {
            return back()->with('error', 'Please select at least one filter option.');
        }

        session(['filter' => $data]);
        return redirect()->route('results-view');
    }

    public function viewResults()
    {
        $filters = session('filter');

        if (!$filters || empty(array_filter($filters))) {
            return redirect()->back()->with('error', 'Please apply at least one filter to view results.');
        }

        if (!empty($filters['level_id'])) {
            $filters['level_name'] = ClassLevel::find($filters['level_id'])->level_name ?? '-';
        }

        if (!empty($filters['class_id'])) {
            $class = SchoolClass::with('level', 'stream')->find($filters['class_id']);
            if ($class) {
                $filters['class_name'] = ($class->level->level_name ?? '-') . ' - ' . ($class->stream->name ?? '-');
            } else {
                $filters['class_name'] = '-';
            }
        }

        if (!empty($filters['exam_id'])) {
            $filters['name'] = Exam::find($filters['exam_id'])->name ?? '-';
        }
        if (!empty($filters['term_id'])) {
            $filters['term_name'] = Term::find($filters['term_id'])->term_name ?? '-';
        }

        $query = Result::with(['student.class.level', 'subject', 'exam', 'term']);

        if (!empty($filters['class_id'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('class_id', $filters['class_id']);
            });
        }

        if (!empty($filters['level_id'])) {
            $query->whereHas('student.class', function ($q) use ($filters) {
                $q->where('level_id', $filters['level_id']);
            });
        }

        if (!empty($filters['exam_id'])) {
            $query->where('exam_id', $filters['exam_id']);
        }
        if (!empty($filters['term_id'])) {
            $query->where('term_id', $filters['term_id']);
        }
        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            return redirect()->route('results-filter')->with('error', 'No results found for the selected criteria.');
        }

        $groupedResults = $results->groupBy(fn($r) => $r->student->id);

        $gradeToScore = function($grade) {
            return match(strtolower(trim($grade))) {
                'below expectation' => 12,
                'approaching expectation' => 37,
                'meeting expectation' => 62,
                'exceeding expectation' => 87,
                default => null
            };
        };

        $studentsWithAverage = [];

        foreach ($groupedResults as $studentId => $resultsForStudent) {
            $student = $resultsForStudent->first()->student;

            $assignedSubjectIds = DB::table('student_subjects')
                ->where('student_id', $studentId)
                ->pluck('subject_id')
                ->toArray();

            $totalScore = 0;
            $countSubjects = count($assignedSubjectIds);

            foreach ($assignedSubjectIds as $subjectId) {
                $result = $resultsForStudent->firstWhere('subject_id', $subjectId);

                if ($result) {
                    $score = $result->marks ?? $gradeToScore($result->grade) ?? 0;
                    $totalScore += $score;
                }
            }

            $averageScore = $countSubjects > 0 ? $totalScore / $countSubjects : 0;

            $studentsWithAverage[] = [
                'student' => $student,
                'results' => $resultsForStudent,
                'average_score' => $averageScore,
            ];
        }

        usort($studentsWithAverage, fn($a, $b) => $b['average_score'] <=> $a['average_score']);

        return view('results-view', compact('studentsWithAverage', 'filters'));
    }

    public function show(Result $result)
    {
        //
    }

    public function edit(Result $result)
    {
        //
    }

    public function update(Request $request, Result $result)
    {
        //
    }

    public function destroy(Result $result)
    {
        //
    }
}
