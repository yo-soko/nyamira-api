<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\Term;
use App\Models\SchoolClass;

class FeePaymentsController extends Controller
{
    public function index()
    {

        $payments = FeePayment::with(['student.schoolClass.level', 'student.schoolClass.stream', 'term'])->latest()->get();

        $students = Student::with(['schoolClass.level', 'schoolClass.stream', 'term'])->get();

        $classLevels = SchoolClass::with(['level', 'stream'])->get();

        $terms = Term::orderBy('year', 'desc')->orderBy('term_name')->get();

        return view('fees.fee-payments', compact('payments', 'students', 'classLevels', 'terms'));
    }

    public function fetchStudents(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)
            ->where('term_id', $request->term_id)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name,
                ];
            });

        return response()->json($students);
    }

    public function fetchBalance(Request $request)
    {
        $studentId = $request->student_id;
        $termId = $request->term_id;
        $classId = $request->class_id;

        // Calculate total fees expected for this class and term (assume you have logic)
        $expected = 20000; // Replace with dynamic logic if available

        // Get total payments made
        $paid = FeePayment::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->where('class_id', $classId)
            ->sum('amount_paid');

        return response()->json([
            'balance' => number_format(($expected - $paid), 2)
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
            'description' => 'nullable|string'
        ]);

        FeePayment::create([
            'student_id' => $request->student_id,
            'class_id' => $request->class_id,
            'term_id' => $request->term_id,
            'payment_mode' => $request->payment_mode,
            'amount_paid' => $request->amount_paid,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Payment added successfully.');
    }




    // Later: store(), update(), destroy() etc.
}
