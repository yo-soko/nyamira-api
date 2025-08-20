<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Term;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\ExamSubjectsClasses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with([
            'term',
            'examSubjectsClasses.subject',
            'examSubjectsClasses.schoolClass.level',
            'examSubjectsClasses.schoolClass.stream',
        ])->get();

        $terms = Term::all();
        $classes = SchoolClass::with(['level', 'stream'])->get();

        return view('exams', compact('exams', 'terms', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'term_id'   => 'required|exists:terms,id',
            'class_ids' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $exam = Exam::create([
                'name'        => $request->name,
                'term_id'     => $request->term_id,
                'user_id'     => auth()->id(),
                'status'      => $request->boolean('status'),
                'is_analysed' => $request->boolean('is_analysed'),
            ]);

            // Fetch all selected classes with subjects in one query
            $schoolClasses = SchoolClass::with(['level', 'subjects'])
                ->whereIn('id', $request->class_ids)
                ->get();

            foreach ($schoolClasses as $schoolClass) {
                foreach ($schoolClass->subjects as $subject) {
                    ExamSubjectsClasses::create([
                        'exam_id'        => $exam->id,
                        'subject_id'     => $subject->id,
                        'term_id'        => $request->term_id,
                        'level_id'       => $schoolClass->level_id,
                        'school_class_id'=> $schoolClass->id,
                        'status'         => 1,
                    ]);
                }
            }
        });

        return redirect()->route('exams')->with('success', 'Exam created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'term_id'           => 'required|exists:terms,id',
            'subject_class_map' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $id) {
            $exam = Exam::findOrFail($id);

            $exam->update([
                'name'        => $request->name,
                'term_id'     => $request->term_id,
                'status'      => $request->boolean('status'),
                'is_analysed' => $request->boolean('is_analysed'),
            ]);

            // Remove old mappings
            $exam->examSubjectsClasses()->delete();

            // Prepare mappings
            foreach ($request->subject_class_map as $pair) {
                [$class_id, $subject_id] = explode(':', $pair);
                $class_id   = (int) $class_id;
                $subject_id = (int) $subject_id;

                $schoolClass = SchoolClass::with('level')->find($class_id);
                if ($schoolClass) {
                    ExamSubjectsClasses::create([
                        'exam_id'        => $exam->id,
                        'subject_id'     => $subject_id,
                        'term_id'        => $request->term_id,
                        'level_id'       => $schoolClass->level_id,
                        'school_class_id'=> $class_id,
                        'status'         => 1,
                    ]);
                }
            }
        });

        return redirect()->route('exams')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $exam = Exam::findOrFail($id);
            $exam->examSubjectsClasses()->delete();
            $exam->delete();
        });

        return redirect()->route('exams')->with('success', 'Exam deleted successfully.');
    }

    public function getSubjectsForClasses($ids)
    {
        $classIds = explode(',', $ids);

        $subjects = Subject::whereHas('classes', function ($q) use ($classIds) {
            $q->whereIn('school_classes.id', $classIds);
        })->select('id', 'subject_name')->get();

        return response()->json($subjects);
    }
}
