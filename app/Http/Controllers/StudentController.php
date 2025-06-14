<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\FeeStructure;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['class.level', 'class.stream', 'term'])->get();

        return view('students', compact('students'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }

    // AJAX: Get students by class ID
    public function getByClass($classId)
    {
        $students = Student::where('class_id', $classId)
            ->select('id', 'first_name', 'middle_name', 'last_name')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'full_name' => trim("{$student->first_name} {$student->middle_name} {$student->last_name}")
                ];
            });

        return response()->json($students);
    }

    // AJAX: Get balance for student and term
    public function getBalance($studentId, $termId)
    {
        $student = Student::findOrFail($studentId);
        $classId = $student->class_id;

        $totalFees = FeeStructure::where('class_id', $classId)
            ->where('term_id', $termId)
            ->sum('amount');

        $totalPaid = Payment::where('student_id', $studentId)
            ->where('term_id', $termId)
            ->sum('amount_paid');

        return response()->json([
            'expected' => $totalFees,
            'paid' => $totalPaid,
            'balance' => $totalFees - $totalPaid,
        ]);
    }

}
