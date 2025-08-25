<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->get('class_id');
        $query = Timetable::with(['class','subject','teacher']);

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $timetables = $query->get()->groupBy('day_of_week');
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('timetable', compact('timetables','classes','subjects','teachers','classId'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // prevent overlap for same class/teacher
        $exists = Timetable::where('class_id', $request->class_id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function($q) use($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($exists) {
            return back()->with('error', 'This time slot is already taken.');
        }

        Timetable::create($request->all());

        return back()->with('success','Timetable entry added.');
    }
}
