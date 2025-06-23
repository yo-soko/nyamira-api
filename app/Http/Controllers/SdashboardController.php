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

        // Summary
        $totalSubjects = $marks->count();
        $totalMarks = $marks->sum('marks');
        $average = $totalSubjects ? round($totalMarks / $totalSubjects, 2) : 0;

        $overallGrade = match (true) {
            $average >= 80 => 'A',
            $average >= 70 => 'B',
            $average >= 60 => 'C',
            $average >= 50 => 'D',
            default => 'E'
        };

        $summary = [
            'total_subjects' => $totalSubjects,
            'total_marks' => $totalMarks,
            'average' => $average,
            'grade' => $overallGrade,
            'exam_name' => $latestExam->exam->name ?? 'N/A',
            'term_name' => $latestExam->term->name ?? 'N/A',
        ];

        return view('sdashboard', compact('marks', 'summary'));
    }
}
