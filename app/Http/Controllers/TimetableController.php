<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\TeacherSubject;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimetableController extends Controller
{
    /**
     * Display timetables for a selected class
     */
    public function index(Request $request)
    {
        $classId = $request->input('class_id');

        $classes = SchoolClass::with(['level', 'stream'])->get();

        $timetables = collect();
        $selectedClass = null;

        if ($classId) {
            $selectedClass = SchoolClass::with(['level', 'stream'])->find($classId);

            $timetables = Timetable::where('class_id', $classId)
                ->with(['teacher', 'subject'])
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();
        }

        return view('timetable', compact('classes', 'timetables', 'selectedClass', 'classId'));
    }

    /**
     * Auto-generate timetable for a given class
     */
    public function autoGenerate(Request $request)
    {
        $classId = $request->input('class_id');
        $class = SchoolClass::with(['level', 'stream'])->findOrFail($classId);

        // Get subjects + teacher assignments
        $assignments = TeacherSubject::where('class_id', $classId)
            ->with(['teacher', 'subject'])
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'No subjects/teachers linked to this class. Please link them first.');
        }

        // Days of week
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $startTime = "08:00"; // first lesson
        $lessonDuration = 40; // minutes per lesson
        $lessonsPerDay = 6;   // adjust to your school system

        // Clear old timetable
        Timetable::where('class_id', $classId)->delete();

        $dayIndex = 0;
        $lessonIndex = 0;

        foreach ($assignments as $assignment) {
            // Calculate time slot
            $currentDay = $days[$dayIndex];
            $time = Carbon::createFromFormat('H:i', $startTime)->addMinutes($lessonIndex * $lessonDuration);

            Timetable::create([
                'class_id'   => $classId,
                'teacher_id' => $assignment->teacher_id,
                'subject_id' => $assignment->subject_id,
                'day_of_week'=> $currentDay,
                'start_time' => $time->format('H:i'),
                'end_time'   => $time->copy()->addMinutes($lessonDuration)->format('H:i'),
            ]);

            $lessonIndex++;
            if ($lessonIndex >= $lessonsPerDay) {
                $lessonIndex = 0;
                $dayIndex++;
                if ($dayIndex >= count($days)) {
                    break; // timetable full
                }
            }
        }

        return redirect()->route('timetable.index', ['class_id' => $classId])
            ->with('success', 'Timetable auto-generated successfully.');
    }

    /**
     * Delete timetable entry
     */
    public function destroy($id)
    {
        $timetable = Timetable::findOrFail($id);
        $timetable->delete();

        return redirect()->back()->with('success', 'Timetable entry deleted successfully.');
    }
}
