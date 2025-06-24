<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeStructure;
use App\Models\ClassLevel;
use App\Models\Term;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Stream;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with('classLevel', 'term')->get();
        $classLevels = ClassLevel::all();
        $terms = Term::all();

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

        // Save the fee structure
        $fee = new FeeStructure();
        $fee->level_id = $request->level_id;
        $fee->term_id = $request->term_id;
        $fee->amount = $request->amount;
        $fee->description = $request->description;
        $fee->status = $request->feeStatus;
        $fee->save();

        // Update student balances
        $students = \App\Models\Student::whereHas('schoolClass', function ($query) use ($request) {
            $query->where('level_id', $request->level_id);
        })->where('term_id', $request->term_id)->get();

        foreach ($students as $student) {
            // Calculate total previous unpaid balance
            $previousUnpaid = \App\Models\FeePayment::where('student_id', $student->id)
                ->where('term_id', '<', $request->term_id)
                ->sum('amount_paid');

            $previousExpected = FeeStructure::where('level_id', $request->level_id)
                ->where('term_id', '<', $request->term_id)
                ->sum('amount');

            $hasNoArrears = ($previousExpected - $previousUnpaid) <= 0;

            if ($hasNoArrears) {
                $student->current_balance += $fee->amount;
                $student->save();
            }
        }

        return response()->json(['success' => true]);
    }


    public function studentPayments(Student $student)
    {
        $payments = FeePayment::with(['term', 'schoolClass.level', 'schoolClass.stream'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('fees.student-payments', compact('student', 'payments'));
    }



    public function show(Request $request)
    {
        $fee = FeeStructure::find($request->fee_id);

        if ($fee) {
            return response()->json([
                'success' => true,
                'data' => $fee
            ]);
        }

        return response()->json(['success' => false, 'error' => 'Fee not found']);
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

        $fee = FeeStructure::find($request->fee_id);

        // If amount changed, calculate the difference
        $originalAmount = $fee->amount;
        $newAmount = $request->amount;
        $difference = $newAmount - $originalAmount;

        $fee->level_id = $request->level_id;
        $fee->term_id = $request->term_id;
        $fee->amount = $newAmount;
        $fee->description = $request->description;
        $fee->status = $request->feeStatus;
        $fee->save();

        // Update student balances if amount changed
        if ($difference != 0) {
            $students = \App\Models\Student::whereHas('schoolClass', function ($query) use ($request) {
                $query->where('level_id', $request->level_id);
            })->where('term_id', $request->term_id)->get();

            foreach ($students as $student) {
                // Check if the student has no unpaid balances from previous terms
                $previousUnpaid = \App\Models\FeePayment::where('student_id', $student->id)
                    ->where('term_id', '<', $request->term_id)
                    ->sum('amount_paid');

                $previousExpected = FeeStructure::where('level_id', $request->level_id)
                    ->where('term_id', '<', $request->term_id)
                    ->sum('amount');

                $hasNoArrears = ($previousExpected - $previousUnpaid) <= 0;

                if ($hasNoArrears) {
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

        $students = \App\Models\Student::whereHas('schoolClass', function ($query) use ($fee) {
            $query->where('level_id', $fee->level_id);
        })->where('term_id', $fee->term_id)->get();

        foreach ($students as $student) {
            // Check if the student had no arrears before this term
            $previousExpected = FeeStructure::where('level_id', $fee->level_id)
                ->where('term_id', '<', $fee->term_id)
                ->sum('amount');

            $previousPaid = \App\Models\FeePayment::where('student_id', $student->id)
                ->where('term_id', '<', $fee->term_id)
                ->sum('amount_paid');

            $hasNoPreviousArrears = ($previousExpected - $previousPaid) <= 0;

            if ($hasNoPreviousArrears) {
                $student->current_balance -= $fee->amount;
                $student->save();
            }
        }

        $fee->delete();

        return response()->json(['success' => true]);
    }

}
