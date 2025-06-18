<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('exams')->get();
        return view('subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code|max:50',
            'subject_name' => 'required',
        ]);

        Subject::create($request->only('subject_code', 'subject_name', 'status'));
        return redirect()->back()->with('success', 'Subject added successfully!');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_code' => 'required|max:50|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required',
        ]);

        $subject->update($request->only('subject_code', 'subject_name', 'status'));
        return redirect()->back()->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->back()->with('success', 'Subject deleted successfully!');
    }
}
