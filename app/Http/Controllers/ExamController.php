<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Term;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\ExamSubjectsClasses;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with(['term', 'examSubjectsClasses.subject', 'examSubjectsClasses.level'])->get();
        $terms = Term::all();
        $subjects = Subject::all();
        $levels = ClassLevel::all();

        return view('exams', compact('exams', 'terms', 'subjects', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'term_id' => 'required|exists:terms,id',
            'subject_ids' => 'required|array',
            'class_ids' => 'required|array',
        ]);

        $exam = Exam::create([
            'name' => $request->name,
            'term_id' => $request->term_id,
            'user_id' => auth()->id(),
            'status' => $request->has('status'),
            'is_analysed' => $request->has('is_analysed'),
        ]);

        foreach ($request->subject_ids as $subject_id) {
            foreach ($request->class_ids as $level_id) {
                ExamSubjectsClasses::create([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject_id,
                    'term_id' => $request->term_id,
                    'level_id' => $level_id,
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
            'subject_ids' => 'required|array',
            'class_ids' => 'required|array',
        ]);

        $exam = Exam::findOrFail($id);

        $exam->update([
            'name' => $request->name,
            'term_id' => $request->term_id,
            'status' => $request->has('status'),
            'is_analysed' => $request->has('is_analysed'),
        ]);

        // remove old
        $exam->examSubjectsClasses()->delete();

        foreach ($request->subject_ids as $subject_id) {
            foreach ($request->class_ids as $level_id) {
                ExamSubjectsClasses::create([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject_id,
                    'term_id' => $request->term_id,
                    'level_id' => $level_id,
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
}
