<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacherUser = Auth::user();
        $teacher = $teacherUser->teacher;

        if (!$teacher) {
            return view('teacher-dashboard', [
                'subject_count'     => 0,
                'submitted_exams'   => 0,
                'pending_exams'     => 0,
                'assignedSubjects'  => collect(),
                'submittedExams'    => collect(),
                'pendingExams'      => collect(),
            ]);
        }

        $assignedSubjects = $teacher->subjects;
        $subject_count = $assignedSubjects->count();

        // collect subject-class pairs from pivot
        $subjectClassPairs = $assignedSubjects->map(function($subject){
            return [
                'subject_id' => $subject->id,
                'class_id'   => $subject->pivot->class_id
            ];
        });

        // get all exams for assigned subjects
        $examSubjects = DB::table('exam_subjects_classes')
            ->join('exams', 'exam_subjects_classes.exam_id', '=', 'exams.id')
            ->join('subjects', 'exam_subjects_classes.subject_id', '=', 'subjects.id')
            ->select(
                'exams.id as exam_id',
                'exams.name as exam_name',
                'subjects.id as subject_id',
                'subjects.subject_name'
            )
            ->whereIn('exam_subjects_classes.subject_id', $assignedSubjects->pluck('id'))
            ->get();

        // add the teacher's class from pivot to each
     $assignedExamSubjects = collect();

        foreach ($examSubjects as $exam) {
            foreach ($subjectClassPairs as $pair) {
                if ($pair['subject_id'] == $exam->subject_id) {
                    $key = $exam->exam_id . '-' . $exam->subject_id . '-' . $pair['class_id'];
                    if (!$assignedExamSubjects->has($key)) {
                        $assignedExamSubjects->put($key, (object)[
                            'exam_id'      => $exam->exam_id,
                            'exam_name'    => $exam->exam_name,
                            'subject_id'   => $exam->subject_id,
                            'subject_name' => $exam->subject_name,
                            'class_id'     => $pair['class_id'],
                        ]);
                    }
                }
            }
        }

        // convert back to values collection
        $assignedExamSubjects = $assignedExamSubjects->values();


        // submitted exams with subject+class
        $submittedExams = DB::table('results')
            ->join('exams', 'results.exam_id', '=', 'exams.id')
            ->join('subjects', 'results.subject_id', '=', 'subjects.id')
            ->select(
                'results.exam_id',
                'results.subject_id',
                'results.class_id',
                DB::raw('COUNT(results.id) as submissions'),
                'exams.name as exam_name',
                'subjects.subject_name'
            )
            ->where('results.user_id', $teacherUser->id)
            ->groupBy('results.exam_id', 'results.subject_id', 'results.class_id', 'exams.name', 'subjects.subject_name')
            ->get();

        // pending = assigned minus submitted
        $pendingExams = $assignedExamSubjects->filter(function ($assigned) use ($submittedExams) {
            return !$submittedExams->first(function ($submitted) use ($assigned) {
                return $submitted->exam_id == $assigned->exam_id &&
                       $submitted->subject_id == $assigned->subject_id &&
                       $submitted->class_id == $assigned->class_id;
            });
        });

        return view('teacher-dashboard', [
            'subject_count'     => $subject_count,
            'submitted_exams'   => $submittedExams->count(),
            'pending_exams'     => $pendingExams->count(),
            'assignedSubjects'  => $assignedSubjects,
            'submittedExams'    => $submittedExams,
            'pendingExams'      => $pendingExams,
        ]);
    }
}
