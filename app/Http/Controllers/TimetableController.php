<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    // Show timetable
    public function index(Request $request)
    {
        $classId = $request->get('class_id');
        $query = Timetable::with(['class','subject','teacher']);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        // group timetables by day_of_week
        $timetables = $query->orderBy('day_of_week')->orderBy('start_time')->get()->groupBy('day_of_week');

        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('timetable', compact('timetables','classes','subjects','teachers','classId'));
    }

    // Show create form
    public function create()
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('timetable_create', compact('classes','subjects','teachers'));
    }

    // Store new timetable
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        Timetable::create($request->all());

        return redirect()->route('timetable')->with('success','Timetable added successfully.');
    }

    // Show edit form
    public function edit(Timetable $timetable)
    {
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('timetable_edit', compact('timetable','classes','subjects','teachers'));
    }

    // Update timetable
    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $timetable->update($request->all());

        return redirect()->route('timetable')->with('success','Timetable updated successfully.');
    }

    // Delete timetable
    public function destroy(Timetable $timetable)
    {
        $timetable->delete();
        return redirect()->route('timetable')->with('success','Timetable deleted successfully.');
    }
}
