<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\ClassLevel;
use App\Models\Stream;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with(['level', 'stream', 'classTeacher', 'classPrefect'])->get();
        return view('school-classes', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stream_id' => 'nullable|exists:streams,id',
            'class_teacher' => 'nullable|exists:users,id',
            'class_prefect' => 'nullable',
            'capacity' => 'required|integer|min:0',
            'status' => 'boolean',
            'level_id' => 'nullable|exists:class_levels,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',

        ]);

          $schoolClass = SchoolClass::create($request->only([
             'stream_id', 'class_teacher', 'class_prefect', 'capacity', 'status', 'level_id'
            ]));

            if ($request->filled('class_teacher')) {
                User::where('id', $request->class_teacher)
                    ->update(['role' => 'class_teacher']);
            }

            // Sync subjects
            if ($request->has('subjects')) {
                $schoolClass->subjects()->sync($request->subjects);
            }

        return redirect()->back()->with('success', 'Class created successfully.');
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $request->validate([
            'stream_id' => 'nullable|exists:streams,id',
            'class_teacher' => 'nullable|exists:users,id',
            'class_prefect' => 'nullable',
            'capacity' => 'required|integer|min:0',
            'status' => 'boolean',
            'level_id' => 'nullable|exists:class_levels,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

           $schoolClass->update($request->only([
            'stream_id', 'class_teacher', 'class_prefect', 'capacity', 'status', 'level_id'
        ]));

        if ($request->filled('class_teacher')) {
            User::where('id', $request->class_teacher)
                ->update(['role' => 'class_teacher']);
        }

        // Sync subjects
        if ($request->has('subjects')) {
            $schoolClass->subjects()->sync($request->subjects);
        }

        $students = $schoolClass->students; // assuming relation is set up

        foreach ($students as $student) {
            $student->subjects()->sync($request->subjects); // assumes subjects() is a belongsToMany
        }
        
        return redirect()->back()->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();
        return redirect()->back()->with('success', 'Class deleted successfully.');
    }
}
