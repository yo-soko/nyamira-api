<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

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
    $termId = $student->term_id;

    // Get the most recent exam the student has marks for in this term
    $latestExam = Result::where('student_id', $studentId)
        ->where('term_id', $termId)
        ->orderByDesc('created_at')
        ->first();

    if (!$latestExam) {
        return view('sdashboard', [
            'marks' => collect(),
            'exam' => null,
            'summary' => [],
        ]);
    }

    $examId = $latestExam->exam_id;

    // Fetch all marks for that student in this exam and term
    $marks = Result::with('subject')
        ->where('student_id', $studentId)
        ->where('term_id', $termId)
        ->where('exam_id', $examId)
        ->get();

    // Fallback grade-to-score logic
    $gradeToScore = function($grade) {
        return match(strtolower(trim($grade))) {
            'below expectation' => 12,
            'approaching expectation' => 37,
            'meeting expectation' => 62,
            'exceeding expectation' => 87,
            default => null,
        };
    };

    $totalScore = 0;
    $countSubjects = 0;

    foreach ($marks as $mark) {
        if (!is_null($mark->marks)) {
            $totalScore += $mark->marks;
            $countSubjects++;
        } elseif (!empty($mark->grade)) {
            $score = $gradeToScore($mark->grade);
            if (!is_null($score)) {
                $totalScore += $score;
                $countSubjects++;
            }
        }
    }

    $average = $countSubjects > 0 ? round($totalScore / $countSubjects, 2) : 0;

    $overallGrade = match (true) {
        $average >= 75 && $average <= 100 => 'E.E',
        $average >= 50 && $average < 75 => 'M.E',
        $average >= 25 && $average < 50 => 'A.E',
        $average >= 0 && $average < 25 => 'B.E',
        default => '-'
    };


    $summary = [
        'total_subjects' => $countSubjects,
        'total_marks' => $totalScore,
        'average' => $average,
        'grade' => $overallGrade,
        'exam_name' => $latestExam->exam->name ?? 'N/A',
        'term_name' => $latestExam->term->name ?? 'N/A',
    ];

    return view('sdashboard', compact('marks', 'summary','student'));
}

}
