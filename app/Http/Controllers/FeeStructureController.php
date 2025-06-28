<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeStructure;
use App\Models\ClassLevel;
use App\Models\Term;
use App\Models\FeePayment;
use App\Models\Student;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with('classLevel', 'term')->latest()->get();
        $classLevels = ClassLevel::all();
        $terms = Term::orderBy('year', 'desc')->orderBy('term_name')->get();

        return view('fees.fee_structure', compact('feeStructures', 'classLevels', 'terms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:class_levels,id',
            'term_id' => 'required|exists:terms,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'feeStatus' => 'required|in:active,inactive',
        ]);

        $fee = FeeStructure::create([
            'level_id' => $request->level_id,
            'term_id' => $request->term_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $request->feeStatus,
        ]);

        $students = Student::whereHas('schoolClass', function ($query) use ($request) {
            $query->where('level_id', $request->level_id);
        })->where('term_id', $request->term_id)->get();

        foreach ($students as $student) {
            $previousExpected = FeeStructure::where('level_id', $request->level_id)
                ->where('term_id', '<', $request->term_id)
                ->sum('amount');

            $previousPaid = FeePayment::where('student_id', $student->id)
                ->where('term_id', '<', $request->term_id)
                ->sum('amount_paid');

            if (($previousExpected - $previousPaid) <= 0) {
                $student->current_balance += $fee->amount;
                $student->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function show(Request $request)
    {
        $fee = FeeStructure::find($request->fee_id);

        return $fee
            ? response()->json(['success' => true, 'data' => $fee])
            : response()->json(['success' => false, 'error' => 'Fee not found']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'fee_id' => 'required|exists:fee_structures,id',
            'level_id' => 'required|exists:class_levels,id',
            'term_id' => 'required|exists:terms,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'feeStatus' => 'required|in:active,inactive',
        ]);

        $fee = FeeStructure::findOrFail($request->fee_id);
        $difference = $request->amount - $fee->amount;

        $fee->update([
            'level_id' => $request->level_id,
            'term_id' => $request->term_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $request->feeStatus,
        ]);

        if ($difference != 0) {
            $students = Student::whereHas('schoolClass', function ($query) use ($request) {
                $query->where('level_id', $request->level_id);
            })->where('term_id', $request->term_id)->get();

            foreach ($students as $student) {
                $previousExpected = FeeStructure::where('level_id', $request->level_id)
                    ->where('term_id', '<', $request->term_id)
                    ->sum('amount');

                $previousPaid = FeePayment::where('student_id', $student->id)
                    ->where('term_id', '<', $request->term_id)
                    ->sum('amount_paid');

                if (($previousExpected - $previousPaid) <= 0) {
                    $student->current_balance += $difference;
                    $student->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $fee = FeeStructure::find($request->fee_id);

        if (!$fee) {
            return response()->json(['success' => false, 'error' => 'Fee not found']);
        }

        $students = Student::whereHas('schoolClass', function ($query) use ($fee) {
            $query->where('level_id', $fee->level_id);
        })->where('term_id', $fee->term_id)->get();

        foreach ($students as $student) {
            $previousExpected = FeeStructure::where('level_id', $fee->level_id)
                ->where('term_id', '<', $fee->term_id)
                ->sum('amount');

            $previousPaid = FeePayment::where('student_id', $student->id)
                ->where('term_id', '<', $fee->term_id)
                ->sum('amount_paid');

            if (($previousExpected - $previousPaid) <= 0) {
                $student->current_balance -= $fee->amount;
                $student->save();
            }
        }

        $fee->delete();

        return response()->json(['success' => true]);
    }

    public function studentPayments(Student $student)
    {
        $payments = FeePayment::with(['term', 'schoolClass.level', 'schoolClass.stream'])
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return view('fees.student-payments', compact('student', 'payments'));
    }
}
