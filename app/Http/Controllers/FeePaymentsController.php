<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\FeeStructure;

class FeePaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = FeePayment::with(['student.schoolClass.level', 'student.schoolClass.stream', 'term']);

        // Filter by Stream
        if ($request->filled('stream_id')) {
            $query->whereHas('student.schoolClass', function ($q) use ($request) {
                $q->where('stream_id', $request->stream_id);
            });
        }

        // Filter by Student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $payments = $query->latest()->get();

        // Get all streams
        $streams = Stream::all();

        // Get all students for the dropdown
        $students = Student::with(['schoolClass.level', 'schoolClass.stream'])->get();

        // Apply balance filters to students
        if ($request->filled('balance_status')) {
            if ($request->balance_status === 'with') {
                $students = $students->filter(fn($s) => $s->current_balance > 0);
            } elseif ($request->balance_status === 'none') {
                $students = $students->filter(fn($s) => $s->current_balance <= 0);
            }
        }

        $classLevels = SchoolClass::with(['level', 'stream'])->get();
        $terms = Term::orderBy('year', 'desc')->orderBy('term_name')->get();

        return view('fees.fee-payments', compact('payments', 'students', 'streams', 'classLevels', 'terms'));
    }

    public function fetchStudents(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)
            ->where('term_id', $request->term_id)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => "{$student->first_name} {$student->last_name}",
                ];
            });

        return response()->json($students);
    }

    public function fetchBalance(Request $request)
    {
        $studentId = $request->student_id;
        $termId = $request->term_id;
        $classId = $request->class_id;

        // Find the student
        $student = Student::with('schoolClass')->findOrFail($studentId);

        // Determine expected fee based on the class's level and term
        $levelId = $student->schoolClass->level_id ?? null;

        if (!$levelId) {
            return response()->json(['balance' => '0.00']);
        }

        $expected = \App\Models\FeeStructure::where('level_id', $levelId)
            ->where('term_id', $termId)
            ->sum('amount');

        // Sum of payments made
        $paid = FeePayment::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('class_id', $classId)
            ->sum('amount_paid');

        $balance = $expected - $paid;

        return response()->json([
            'balance' => number_format($balance, 2)
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:school_classes,id',
            'term_id' => 'required|exists:terms,id',
            'payment_mode' => 'required|string',
            'amount_paid' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'receipt_number' => 'nullable|string'

        ]);

        FeePayment::create([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'term_id' => $request->term_id,
            'payment_mode' => $request->payment_mode,
            'amount_paid' => $request->amount_paid,
            'receipt_number' => uniqid('RCP-'),
            'description' => $request->description,
        ]);

        return redirect()->route('student.payments', $request->student_id)
            ->with('success', 'Payment added successfully.');

    }




    // Later: store(), update(), destroy() etc.
}
