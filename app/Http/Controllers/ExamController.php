<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Term;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\ExamSubjectsClasses;
use Illuminate\Http\Request;

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
            'name' => 'required|string|max:255',
            'term_id' => 'required|exists:terms,id',
            'subject_class_map' => 'required|array',
        ]);

        $exam = Exam::create([
            'name' => $request->name,
            'term_id' => $request->term_id,
            'user_id' => auth()->id(),
            'status' => $request->has('status'),
            'is_analysed' => $request->has('is_analysed'),
        ]);

        foreach ($request->subject_class_map as $pair) {
            [$class_id, $subject_id] = explode(':', $pair);
            $schoolClass = SchoolClass::with('level')->find($class_id);
            if ($schoolClass) {
                ExamSubjectsClasses::create([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject_id,
                    'term_id' => $request->term_id,
                    'level_id' => $schoolClass->level_id,
                    'school_class_id' => $class_id,
                    'status' => 1,
                ]);
            }
        }

        return redirect()->route('exams')->with('success', 'Exam created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'term_id' => 'required|exists:terms,id',
            'subject_class_map' => 'required|array',
        ]);

        $exam = Exam::findOrFail($id);

        $exam->update([
            'name' => $request->name,
            'term_id' => $request->term_id,
            'status' => $request->has('status'),
            'is_analysed' => $request->has('is_analysed'),
        ]);

        // clear old links
        $exam->examSubjectsClasses()->delete();

        foreach ($request->subject_class_map as $pair) {
            [$class_id, $subject_id] = explode(':', $pair);
            $schoolClass = SchoolClass::with('level')->find($class_id);
            if ($schoolClass) {
                ExamSubjectsClasses::create([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject_id,
                    'term_id' => $request->term_id,
                    'level_id' => $schoolClass->level_id,
                    'school_class_id' => $class_id,
                    'status' => 1,
                ]);
            }
        }

        return redirect()->route('exams')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->examSubjectsClasses()->delete();
        $exam->delete();

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
