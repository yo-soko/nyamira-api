<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ExamSubjectsClasses;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $teacherModel = $teacher->teacher;

        if (!$teacherModel) {
            return view('teacher-dashboard', [
                'subject_count'    => 0,
                'submitted_exams'  => 0,
                'pending_exams'    => 0,
                'assignedSubjects' => collect(),
                'submittedExams'   => collect(),
                'pendingExams'     => collect(),
            ]);
        }

        $assignedSubjects = $teacherModel->subjects;
        $subject_count = $assignedSubjects->count();

        // get submitted exams from pivot
        $submittedExams = ExamSubjectsClasses::with(['exam', 'subject', 'level', 'term'])
            ->where('status', 1)
            ->whereIn('subject_id', $assignedSubjects->pluck('id'))
            ->get();

        // get pending exams from pivot
        $pendingExams = ExamSubjectsClasses::with(['exam', 'subject', 'level', 'term'])
            ->where('status', 0)
            ->whereIn('subject_id', $assignedSubjects->pluck('id'))
            ->get();

        return view('teacher-dashboard', [
            'subject_count'    => $subject_count,
            'submitted_exams'  => $submittedExams->count(),
            'pending_exams'    => $pendingExams->count(),
            'assignedSubjects' => $assignedSubjects,
            'submittedExams'   => $submittedExams,
            'pendingExams'     => $pendingExams,
        ]);
    }
}
