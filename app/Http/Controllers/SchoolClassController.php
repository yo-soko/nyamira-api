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
        ]);

        SchoolClass::create($request->all());

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
        ]);

        $schoolClass->update($request->all());

        return redirect()->back()->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();
        return redirect()->back()->with('success', 'Class deleted successfully.');
    }
}
