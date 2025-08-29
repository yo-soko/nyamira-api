<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('timetable', compact('timetables'));
    }

    public function autoGenerate()
    {
        $classes  = SchoolClass::with('subjects')->get();
        $subjects = Subject::with('teachers')->get();
        $teachers = Teacher::all();

        if ($classes->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty()) {
            return redirect()->route('timetable.index')
                ->with('error', 'Add at least one Class, Subject and Teacher before auto-generating.');
        }

        $hasAnyLinks = $classes->first(fn($c) => $c->subjects && $c->subjects->isNotEmpty())
            && $subjects->first(fn($s) => $s->teachers && $s->teachers->isNotEmpty());

        if (! $hasAnyLinks) {
            return redirect()->route('timetable.index')
                ->with('error', 'Link subjects to classes and teachers to subjects before auto-generating.');
        }

        DB::table('timetables')->truncate();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = [
            ['08:00', '09:00'],
            ['09:00', '10:00'],
            ['10:00', '11:00'],
            ['11:00', '12:00'],
            ['12:00', '13:00'],
            ['14:00', '15:00'],
            ['15:00', '16:00'],
        ];

        foreach ($classes as $class) {
            $classSubjects = $class->subjects ?? collect();
            if ($classSubjects->isEmpty()) {
                continue;
            }

            foreach ($days as $day) {
                foreach ($timeSlots as [$start, $end]) {
                    $subject = $classSubjects->random();
                    $teacherQuery = $subject->teachers();
                    $teacher = $teacherQuery->inRandomOrder()->first();

                    if (! $teacher) {
                        continue;
                    }

                    $classBusy = Timetable::where('class_id', $class->id)
                        ->where('day_of_week', $day)
                        ->where(function ($q) use ($start, $end) {
                            $q->where('start_time', '<', $end)
                              ->where('end_time',   '>', $start);
                        })
                        ->exists();

                    if ($classBusy) continue;

                    $teacherBusy = Timetable::where('teacher_id', $teacher->id)
                        ->where('day_of_week', $day)
                        ->where(function ($q) use ($start, $end) {
                            $q->where('start_time', '<', $end)
                              ->where('end_time',   '>', $start);
                        })
                        ->exists();

                    if ($teacherBusy) continue;

                    Timetable::create([
                        'class_id'    => $class->id,
                        'subject_id'  => $subject->id,
                        'teacher_id'  => $teacher->id,
                        'day_of_week' => $day,
                        'start_time'  => $start,
                        'end_time'    => $end,
                    ]);
                }
            }
        }

        return redirect()->route('timetable.index')
            ->with('success', 'Timetable auto-generated successfully!');
    }
}
