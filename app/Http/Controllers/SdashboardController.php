<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Student;
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
    private function getRubricCode($score)
    {
        return match((int) $score) {
            4 => 'E.E',
            3 => 'M.E',
            2 => 'A.E',
            1 => 'B.E',
            default => '-'
        };
    }

    public function cbcReport(Request $request)
    {
        $user = auth()->user();

        $student = $request->get('student') ?? $user->student;
        if (!$student) {
            abort(404, 'Student not found');
        }

        $studentId = $student->id;

        $termId = $request->input('term_id', $student->term_id);

        $termIds = \App\Models\Result::where('student_id', $studentId)
            ->pluck('term_id')
            ->unique();

        $terms = \App\Models\Term::whereIn('id', $termIds)
            ->orderBy('start_date', 'desc')
            ->get();

        $studentClass = $student->class;
        $studentClassId = $studentClass->id ?? null;
        $studentLevelId = $studentClass->level_id ?? null;

        // Define exam name mapping
        $assessmentNames = [
            1 => 'Assessment 1',
            2 => 'Assessment 2',
            3 => 'Assessment 3',
        ];

        // Get exams for the selected term
        $exams = \App\Models\Exam::where('term_id', $termId)
            ->whereIn('name', array_values($assessmentNames))
            ->get()
            ->keyBy('name');

        // Fetch all results for this student for those exams
        $results = \App\Models\Result::with('subject')
            ->where('student_id', $studentId)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->where('term_id', $termId)
            ->get()
            ->groupBy('subject_id');

        // Grade-to-score mapping
        $gradeToScore = function ($grade) {
            return match(strtolower(trim($grade))) {
                'below expectation' => 12,
                'approaching expectation' => 37,
                'meeting expectation' => 62,
                'exceeding expectation' => 87,
                default => null,
            };
        };

        $marks = collect();
        $summary = [];

        // Initialize summary
        for ($i = 1; $i <= 3; $i++) {
            $summary["avg_$i"] = '-';
        }

        // Build the subject marks from actual results only
        foreach ($results as $subjectId => $subjectResults) {
            $subject = $subjectResults->first()?->subject ?? \App\Models\Subject::find($subjectId);
            $row = new \stdClass();
            $row->subject = $subject;

            for ($i = 1; $i <= 3; $i++) {
                $exam = $exams[$assessmentNames[$i]] ?? null;

                $row->{"assessment_$i"} = null;
                $row->{"comment_$i"} = null;

                if ($exam) {
                    $result = $subjectResults->firstWhere('exam_id', $exam->id);

                    if ($result) {
                        $score = $result->marks ?? $gradeToScore($result->grade);
                        $row->{"assessment_$i"} = $score;
                        $row->{"comment_$i"} = $result->comments;
                    }
                }
            }

            $marks->push($row);
        }

        // Compute averages per assessment (based only on subjects in results)
        for ($i = 1; $i <= 3; $i++) {
            $total = 0;
            $count = 0;

            foreach ($marks as $mark) {
                $val = $mark->{"assessment_$i"};
                if (!is_null($val)) {
                    $total += $val;
                    $count++;
                }
            }

            $summary["avg_$i"] = $count > 0 ? round($total / $count, 2) : '-';
        }

        // Class and Level average (optional)
        $classAverage = 0;
        $levelAverage = 0;
        for ($i = 1; $i <= 3; $i++) {
            $exam = $exams[$assessmentNames[$i]] ?? null;
            if ($exam) {
                $classAvg = \App\Models\Result::where('exam_id', $exam->id)
                    ->where('term_id', $termId)
                    ->whereHas('student', fn($q) => $q->where('class_id', $studentClassId))
                    ->avg('marks');
                $classAverage = $classAvg ? round($classAvg, 2) : $classAverage;

                $levelAvg = \App\Models\Result::where('exam_id', $exam->id)
                    ->where('term_id', $termId)
                    ->whereHas('student.class', fn($q) => $q->where('level_id', $studentLevelId))
                    ->avg('marks');
                $levelAverage = $levelAvg ? round($levelAvg, 2) : $levelAverage;
            }
        }

        // Rubric label generator
        $rubricCode = function ($score) {
            if ($score === null) return '-';
            if ($score >= 80) return 'E.E';
            if ($score >= 60) return 'M.E';
            if ($score >= 40) return 'A.E';
            if ($score >= 0) return 'B.E';
            return '-';
        };

        // Build chart data
        $chartData = $marks->map(function ($mark) use ($gradeToScore) {
            $score = null;
            for ($i = 3; $i >= 1; $i--) {
                $val = property_exists($mark, "assessment_$i") ? $mark->{"assessment_$i"} : null;
                if (!is_null($val)) {
                    $score = is_numeric($val) ? $val : $gradeToScore($val);
                    break;
                }
            }
            return [
                'subject' => $mark->subject->subject_name ?? 'Unknown',
                'score' => $score ?? 0,
            ];
        });

        // Facilitator detection
        $facilitatorUserId = \App\Models\Result::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->first()?->user_id;

        $facilitator = \App\Models\User::find($facilitatorUserId);
        $facilitatorName = $facilitator ? $facilitator->name : ' ';

        // Term details
        $term = \App\Models\Term::find($termId);
        $summary['term_name'] = $term->term_name ?? '-';
        $summary['term_end_date'] = $term->end_date ?? '-';
        $summary['next_term_begins'] = $term->next_term_begins ?? '-';

        return view('cbc-report', compact(
            'terms',
            'marks',
            'facilitatorName',
            'summary',
            'student',
            'chartData',
            'rubricCode',
            'classAverage',
            'levelAverage',
            'termId'
        ));
    }

    public function viewCBCReport(Request $request)
    {
        $user = auth()->user();

        $student = Student::where('student_reg_number', $request->student_reg_number)
            ->with('class')
            ->firstOrFail();

        if ($user->role === 'class_teacher') {
            $teacherClassIds = SchoolClass::where('class_teacher', $user->id)->pluck('id')->toArray();
            if (!in_array($student->class_id, $teacherClassIds)) {
                abort(403, 'Unauthorized');
            }
        }

        return $this->cbcReport($request->merge(['student' => $student]));
    }


}
