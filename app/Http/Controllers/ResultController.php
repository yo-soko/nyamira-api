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
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $examData = $request->input('exams');
        $termId = $request->input('term_id'); 
        $examId = $request->input('exam_id'); 
        $classId = $request->input('class_id'); 

        $existingStudents = [];

        foreach ($examData as $studentId => $data) {
            $isAbsent = isset($data['absent']) && $data['absent'] == 1;

            if (!$isAbsent && empty($data['grade'])) {
                return back()->with("error","All Grades is required unless marked absent.");
            }

            // Check if result already exists
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
            // Fetch student names to make error message clearer
            $names = Student::whereIn('id', $existingStudents)
                ->pluck('first_name', 'id')
                ->map(function($firstName, $id) use ($existingStudents) {
                    return $firstName;
                })->values()->toArray();

            $namesStr = implode(', ', $names);

            return back()->with("error","Results for the following students have already been submitted: $namesStr");
        }

        // If no existing records found, save all results
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
        // Validate the input
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

        // First, get the class level ID from the class
        $class = SchoolClass::findOrFail($classId); // Assuming model is SchoolClass
        $levelId = $class->level_id;

        // Now check if the selected exam + subject + level exists in exam_subjects_classes
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


        // Pass the data to the view
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

        // Fetch filter names to display in the view
        if (!empty($filters['level_id'])) {
            $filters['level_name'] = ClassLevel::find($filters['level_id'])->level_name ?? '-';
        }
        if (!empty($filters['class_id'])) {
            $class = SchoolClass::with('level', 'stream')->find($filters['class_id']);
            if ($class) {
                $levelName = $class->level->level_name ?? 'No Level';
                $streamName = $class->stream->name ?? 'No Stream';
                $filters['class_name'] = $levelName . ' - ' . $streamName;
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

        $query = Result::with([
            'student.class.level',
            'subject',
            'exam',
            'term'
        ]);

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
            return redirect()->route('results-filter')
                ->with('error', 'No results found for the selected criteria.');
        }

        // Group results by student
        $groupedResults = $results->groupBy(fn($r) => $r->student->id);

        // Define your gradeToScore here or import it properly
        $gradeToScore = function($grade) {
            $normalized = strtolower(trim($grade));
            return match($normalized) {
                'below expectation'        => 12,
                'approaching expectation'  => 37,
                'meeting expectation'      => 62,
                'exceeding expectation'    => 87,
                default => null,
            };
        };


        // Build an array with student data + average score
        $studentsWithAverage = [];

        foreach ($groupedResults as $studentId => $resultsForStudent) {
            $student = $resultsForStudent->first()->student;

            // ✅ Get all subject IDs assigned to this student
            $assignedSubjectIds = DB::table('student_subjects')
                ->where('student_id', $studentId)
                ->pluck('subject_id')
                ->toArray();

            $totalScore = 0;
            $countSubjects = count($assignedSubjectIds); // ✅ always divide by this

            foreach ($assignedSubjectIds as $subjectId) {
                // ✅ Look for a result *only if* it's for an assigned subject
                $result = $resultsForStudent->firstWhere('subject_id', $subjectId);

                if ($result) {
                    if (!is_null($result->marks)) {
                        $totalScore += $result->marks;
                    } elseif (!empty($result->grade)) {
                        $score = $gradeToScore($result->grade);
                        if (!is_null($score)) {
                            $totalScore += $score;
                        }
                    }
                } else {
                    // ✅ No result for assigned subject → treat as 0
                    $totalScore += 0;
                }
            }

            // ✅ Average is based only on assigned subjects
            $averageScore = $countSubjects > 0 ? $totalScore / $countSubjects : 0;

            $studentsWithAverage[] = [
                'student' => $student,
                'results' => $resultsForStudent,
                'average_score' => $averageScore,
                'subjects_assigned' => $countSubjects,
                'results_found' => $resultsForStudent->count(),
            ];
        }


        // Sort descending by average_score
        usort($studentsWithAverage, fn($a, $b) => $b['average_score'] <=> $a['average_score']);

        // Assign rank (handle ties if needed)
        $rank = 0;
        $prevScore = null;
        $skipRank = 0; // for ties

        foreach ($studentsWithAverage as $index => &$studentData) {
            if ($prevScore !== null && $studentData['average_score'] == $prevScore) {
                // same score as previous, same rank
                $studentData['rank'] = $rank;
                $skipRank++;
            } else {
                // new score, increment rank with any skipped ranks for ties
                $rank = $rank + 1 + $skipRank;
                $studentData['rank'] = $rank;
                $skipRank = 0;
            }
            $prevScore = $studentData['average_score'];
        }

        // Pass studentsWithAverage and subjects to the view
        $subjects = $results->pluck('subject')->unique('id')->sortBy('subject_name')->values();

        return view('results-view', compact('studentsWithAverage', 'subjects', 'filters'));

    }




    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
