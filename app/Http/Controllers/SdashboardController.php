<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use Illuminate\Support\Facades\DB;

class SdashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student profile not found.');
        }

        $studentId = $student->id;

        // Get the latest exam the student participated in
        $latestExamResult = Result::where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->first();

        if (!$latestExamResult) {
            return view('sdashboard', [
                'marks' => collect(),
                'summary' => [],
                'student' => $student,
                'chartData' => collect(),
            ]);
        }

        $examId = $latestExamResult->exam_id;
        $termId = $latestExamResult->term_id;
// Get student's class and level
$studentClass = $student->class;
$studentLevelId = $studentClass->level_id ?? null;
$studentClassId = $studentClass->id ?? null;

// Get class average
$classAverage = Result::where('exam_id', $examId)
    ->where('term_id', $termId)
    ->whereHas('student', fn($q) => $q->where('class_id', $studentClassId))
    ->whereNotNull('marks')
    ->avg('marks');

// Get level average
$levelAverage = Result::where('exam_id', $examId)
    ->where('term_id', $termId)
    ->whereHas('student.class', fn($q) => $q->where('level_id', $studentLevelId))
    ->whereNotNull('marks')
    ->avg('marks');
        // Fetch all marks for that student in this exam
        $marks = Result::with('subject')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->where('term_id', $termId)
            ->get();

        // Grade fallback converter
        $gradeToScore = function ($grade) {
            return match(strtolower(trim($grade))) {
                'below expectation' => 12,
                'approaching expectation' => 37,
                'meeting expectation' => 62,
                'exceeding expectation' => 87,
                default => null,
            };
        };

        // Get assigned subjects
        $assignedSubjectIds = DB::table('student_subjects')
            ->where('student_id', $studentId)
            ->pluck('subject_id')
            ->toArray();

        $totalScore = 0;
        $countSubjects = count($assignedSubjectIds);

        foreach ($assignedSubjectIds as $subjectId) {
            $result = $marks->firstWhere('subject_id', $subjectId);

            if ($result) {
                if (!is_null($result->marks)) {
                    $totalScore += $result->marks;
                } elseif (!empty($result->grade)) {
                    $score = $gradeToScore($result->grade);
                    $totalScore += $score ?? 0;
                }
            } else {
                $totalScore += 0; // Treat as zero if no result exists
            }
        }

        $average = $countSubjects > 0 ? round($totalScore / $countSubjects, 2) : 0;

        $overallGrade = match (true) {
            $average >= 80 => 'E.E',
            $average >= 60 => 'M.E',
            $average >= 40 => 'A.E',
            $average > 0   => 'B.E',
            default        => '-',
        };

        $summary = [
            'total_subjects' => $countSubjects,
            'total_marks' => $totalScore,
            'average' => $average,
            'grade' => $overallGrade,
            'exam_name' => $latestExamResult->exam->name ?? 'N/A',
            'term_name' => $latestExamResult->term->name ?? 'N/A',
        ];

        $chartData = $marks->map(function ($mark) use ($gradeToScore) {
            $score = $mark->marks ?? $gradeToScore($mark->grade);
            return [
                'subject' => $mark->subject->subject_name ?? 'Unknown',
                'score' => $score ?? 0,
            ];
        });

        return view('sdashboard', compact('marks', 'summary', 'student', 'chartData', 'classAverage',
    'levelAverage'));
    }

}
