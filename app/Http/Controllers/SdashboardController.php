<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // Calculate average only from subjects that have results
        $totalScore = 0;
        $countSubjectsWithResults = 0;

        foreach ($marks as $result) {
            $score = null;

            if (!is_null($result->marks)) {
                $score = $result->marks;
            } elseif (!empty($result->grade)) {
                $score = $gradeToScore($result->grade);
            }

            if (!is_null($score)) {
                $totalScore += $score;
                $countSubjectsWithResults++;
            }
        }

        $average = $countSubjectsWithResults > 0 ? round($totalScore / $countSubjectsWithResults, 2) : 0;

        $overallGrade = match (true) {
            $average >= 80 => 'E.E',
            $average >= 60 => 'M.E',
            $average >= 40 => 'A.E',
            $average > 0   => 'B.E',
            default        => '-',
        };

        $summary = [
            'total_subjects' => $countSubjectsWithResults,
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

        return view('sdashboard', compact(
            'marks',
            'summary',
            'student',
            'chartData',
            'classAverage',
            'levelAverage'
        ));
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

        $examId = $request->input('exam_id');

        $assessmentNames = [
            1 => 'Assessment 1',
            2 => 'Assessment 2',
            3 => 'Assessment 3',
        ];

        if ($examId) {
            $selectedExam = \App\Models\Exam::find($examId);
            if (!$selectedExam) {
                abort(404, 'Assessment not found');
            }
            $assessmentNames = [1 => $selectedExam->name];
            $exams = collect([$selectedExam])->keyBy('name');
        } else {
            $exams = \App\Models\Exam::where('term_id', $termId)
                ->whereIn('name', array_values($assessmentNames))
                ->get()
                ->keyBy('name');
        }

        $existingAssessments = [];
        foreach ($assessmentNames as $i => $name) {
            if (isset($exams[$name])) {
                $existingAssessments[] = $i;
            }
        }

        $results = \App\Models\Result::with('subject')
            ->where('student_id', $studentId)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->where('term_id', $termId)
            ->get()
            ->groupBy('subject_id');

        $gradeToScore = function ($grade) {
            return match (strtolower(trim($grade))) {
                'below expectation' => 12,
                'approaching expectation' => 37,
                'meeting expectation' => 62,
                'exceeding expectation' => 87,
                default => null,
            };
        };

        $marks = collect();
        $summary = [];

        foreach ($existingAssessments as $i) {
            $summary["avg_$i"] = '-';
        }

        foreach ($results as $subjectId => $subjectResults) {
            $subject = $subjectResults->first()?->subject ?? \App\Models\Subject::find($subjectId);
            $row = new \stdClass();
            $row->subject = $subject;

            foreach ($existingAssessments as $i) {
                $examName = $assessmentNames[$i];
                $exam = $exams[$examName] ?? null;

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

        foreach ($existingAssessments as $i) {
            $total = 0;
            $count = 0;

            foreach ($marks as $mark) {
                $val = property_exists($mark, "assessment_$i") ? $mark->{"assessment_$i"} : null;

                if (!is_null($val)) {
                    $total += $val;
                    $count++;
                }
            }

            $summary["avg_$i"] = $count > 0 ? round($total / $count, 2) : '-';
        }

        $classAverage = 0;
        $levelAverage = 0;

        foreach ($existingAssessments as $i) {
            $examName = $assessmentNames[$i];
            $exam = $exams[$examName] ?? null;

            if ($exam) {
                $classAvg = \App\Models\Result::where('exam_id', $exam->id)
                    ->where('term_id', $termId)
                    ->whereHas('student', fn ($q) => $q->where('class_id', $studentClassId))
                    ->avg('marks');

                $classAverage = $classAvg ? round($classAvg, 2) : $classAverage;

                $levelAvg = \App\Models\Result::where('exam_id', $exam->id)
                    ->where('term_id', $termId)
                    ->whereHas('student.class', fn ($q) => $q->where('level_id', $studentLevelId))
                    ->avg('marks');

                $levelAverage = $levelAvg ? round($levelAvg, 2) : $levelAverage;
            }
        }

        $rubricCode = function ($score) {
            if ($score === null) return '-';
            if ($score >= 80) return 'E.E';
            if ($score >= 60) return 'M.E';
            if ($score >= 40) return 'A.E';
            if ($score >= 0)  return 'B.E';
            return '-';
        };

        $chartData = $marks->map(function ($mark) use ($gradeToScore, $existingAssessments) {
            $score = null;

            foreach (array_reverse($existingAssessments) as $i) {
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

        $facilitatorUserId = \App\Models\Result::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->first()?->user_id;

        $facilitator = \App\Models\User::find($facilitatorUserId);
        $facilitatorName = $facilitator ? $facilitator->name : ' ';

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

    public function cbcReportBatch(Request $request)
    {
        $classId = $request->input('class_id');
        $termId = $request->input('term_id');
        $examId = $request->input('exam_id'); // optional

        $students = \App\Models\Student::where('class_id', $classId)->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found in selected class.');
        }

        $reports = [];

        foreach ($students as $student) {
            // Create a mock request for each student
            $clone = new Request([
                'term_id' => $termId,
                'exam_id' => $examId
            ]);
            $clone->merge(['student' => $student]);

            $reports[] = $this->cbcReport($clone)->getData();
        }

        return view('cbc-batch-report', compact('reports'));
    }

    public function generateBulkReports(Request $request)
    {
        $classId = $request->input('class_id');
        $termId = $request->input('term_id');
        $examId = $request->input('exam_id');

        $students = Student::where('class_id', $classId)->get();

        $reports = [];

        foreach ($students as $student) {
        $reportData = $this->prepareCbcReportData($student, $termId, $examId);

            $reports[] = $reportData;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cbc-reports-bulk-wrapper', [
            'students' => $reports
        ])->setPaper('A4', 'portrait');

        return $pdf->download('cbc-bulk-reports.pdf');
    }

    public function prepareCbcReportData($student, $termId, $examId = null)
    {
        $studentId = $student->id;

        $termIds = \App\Models\Result::where('student_id', $studentId)
            ->pluck('term_id')
            ->unique();

        $terms = \App\Models\Term::whereIn('id', $termIds)
            ->orderBy('start_date', 'desc')
            ->get();

        $studentClass = $student->class;
        $studentClassId = $studentClass->id ?? null;
        $studentLevelId = $studentClass->level_id ?? null;

        $assessmentNames = [
            1 => 'Assessment 1',
            2 => 'Assessment 2',
            3 => 'Assessment 3',
        ];

        if ($examId) {
            $selectedExam = \App\Models\Exam::find($examId);
            if (!$selectedExam) {
                abort(404, 'Assessment not found');
            }

            // Reverse lookup: find key (1/2/3) from name
            $reverseMap = array_flip([
                1 => 'Assessment 1',
                2 => 'Assessment 2',
                3 => 'Assessment 3',
            ]);

            $index = $reverseMap[$selectedExam->name] ?? 1; // default to 1 if not found
            $assessmentNames = [$index => $selectedExam->name];
            $exams = collect([$selectedExam])->keyBy('name');
        }
        else {
            $exams = \App\Models\Exam::where('term_id', $termId)
                ->whereIn('name', array_values($assessmentNames))
                ->get()
                ->keyBy('name');
        }

        $existingAssessments = [];
        foreach ($assessmentNames as $i => $name) {
            if (isset($exams[$name])) {
                $existingAssessments[] = $i;
            }
        }

        $results = \App\Models\Result::with('subject')
            ->where('student_id', $studentId)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->where('term_id', $termId)
            ->get()
            ->groupBy('subject_id');

        $gradeToScore = function ($grade) {
            return match (strtolower(trim($grade))) {
                'below expectation' => 12,
                'approaching expectation' => 37,
                'meeting expectation' => 62,
                'exceeding expectation' => 87,
                default => null,
            };
        };

        $marks = collect();
        $summary = [];

        foreach ($existingAssessments as $i) {
            $summary["avg_$i"] = '-';
        }

        foreach ($results as $subjectId => $subjectResults) {
            $subject = $subjectResults->first()?->subject ?? \App\Models\Subject::find($subjectId);
            $row = new \stdClass();
            $row->subject = $subject;

            foreach ($existingAssessments as $i) {
                $examName = $assessmentNames[$i];
                $exam = $exams[$examName] ?? null;

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

        foreach ($existingAssessments as $i) {
            $total = 0;
            $count = 0;

            foreach ($marks as $mark) {
                $val = property_exists($mark, "assessment_$i") ? $mark->{"assessment_$i"} : null;
                if (!is_null($val)) {
                    $total += $val;
                    $count++;
                }
            }

            $summary["avg_$i"] = $count > 0 ? round($total / $count, 2) : '-';
        }

        $classAverage = 0;
        $levelAverage = 0;

        foreach ($existingAssessments as $i) {
            $examName = $assessmentNames[$i];
            $exam = $exams[$examName] ?? null;

            if ($exam) {
                $classAvg = \App\Models\Result::where('exam_id', $exam->id)
                    ->where('term_id', $termId)
                    ->whereHas('student', fn ($q) => $q->where('class_id', $studentClassId))
                    ->avg('marks');
                $classAverage = $classAvg ? round($classAvg, 2) : $classAverage;

                $levelAvg = \App\Models\Result::where('exam_id', $exam->id)
                    ->where('term_id', $termId)
                    ->whereHas('student.class', fn ($q) => $q->where('level_id', $studentLevelId))
                    ->avg('marks');
                $levelAverage = $levelAvg ? round($levelAvg, 2) : $levelAverage;
            }
        }

        $rubricCode = function ($score) {
            if ($score === null) return '-';
            if ($score >= 80) return 'E.E';
            if ($score >= 60) return 'M.E';
            if ($score >= 40) return 'A.E';
            if ($score >= 0) return 'B.E';
            return '-';
        };

        $chartData = $marks->map(function ($mark) use ($gradeToScore, $existingAssessments) {
            $score = null;

            foreach (array_reverse($existingAssessments) as $i) {
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

        $facilitatorUserId = \App\Models\Result::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->select('user_id', DB::raw('count(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->first()?->user_id;

        $facilitator = \App\Models\User::find($facilitatorUserId);
        $facilitatorName = $facilitator ? $facilitator->name : ' ';

        $term = \App\Models\Term::find($termId);
        $summary['term_name'] = $term->term_name ?? '-';
        $summary['term_end_date'] = $term->end_date ?? '-';
        $summary['next_term_begins'] = $term->next_term_begins ?? '-';

        return compact(
            'student',
            'marks',
            'summary',
            'facilitatorName',
            'chartData',
            'rubricCode',
            'classAverage',
            'levelAverage',
            'termId'
        );
    }
    public function viewBulkReports(Request $request)
    {
        $classId = $request->input('class_id');
        $termId = $request->input('term_id');
        $examId = $request->input('exam_id');

        $students = Student::where('class_id', $classId)->get();
        $reports = [];

        foreach ($students as $student) {
            $reports[] = $this->prepareCbcReportData($student, $termId, $examId);
        }

        return view('cbc.bulk-html-view', compact('reports'));
    }

}
