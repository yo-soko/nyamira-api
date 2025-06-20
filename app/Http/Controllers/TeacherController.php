<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Schoolclass;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['subjects', 'schoolclasses'])->get();
        $subjects = Subject::all();
        $schoolclasses = Schoolclass::all();
        return view('teachers', compact('teachers', 'subjects', 'schoolclasses'));
    }

    public function store(Request $request)
    {
        $teacher = Teacher::create($request->only([
            'first_name', 'last_name', 'date_of_birth', 'email', 'phone',
            'id_no', 'address', 'education_level', 'years_of_experience',
            'gender', 'department', 'status'
        ]));

        foreach ($request->input('subject_class') as $entry) {
            $teacher->subjects()->attach($entry['subject_id'], [
                'schoolclass_id' => $entry['schoolclass_id']
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully!');
    }
}
