<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $query = FeePayment::with(['student', 'classLevel', 'term']);

        // Filter by Stream
        if ($request->filled('stream_id')) {
            $query->whereHas('student.schoolClass', function ($q) use ($request) {
                $q->where('stream_id', $request->stream_id);
            });
        }


        if ($request->filled('filter_class_id')) {
            $query->where('class_id', $request->filter_class_id);
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

        if (!$studentId) {
            return response()->json(['balance' => 0.00]);
        }

        $student = Student::find($studentId);

        if (!$student) {
            return response()->json(['balance' => 0.00]);
        }

        return response()->json([
            'balance' => number_format($student->current_balance, 2)
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'term_id' => 'nullable|exists:terms,id',
            'student_id' => 'required|exists:students,id',
            'payment_mode' => 'required|string',
            'amount_paid' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'receipt_number' => 'nullable|string|unique:fee_payments,receipt_number',
        ]);

        try {
            \Log::info('Attempting to create fee payment', $validated);

            $payment = FeePayment::create([
                'class_id' => $validated['class_id'],
                'term_id' => $validated['term_id'],
                'student_id' => $validated['student_id'],
                'payment_mode' => $validated['payment_mode'],
                'amount_paid' => $validated['amount_paid'],
                'description' => $validated['description'] ?? null,
                'receipt_number' => $validated['receipt_number'] ?? null,
                'user_id' => auth()->id(),
            ]);

            // Deduct from student balance
            $student = Student::find($validated['student_id']);
            $student->current_balance -= $validated['amount_paid'];
            $student->save();

            return redirect()->back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            \Log::error('Payment creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to record payment. Please try again.');
        }

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required|exists:class_levels,id',
            'term_id' => 'required|exists:terms,id',
            'student_id' => 'required|exists:students,id',
            'receipt_number' => 'nullable|string',
            'description' => 'nullable|string',
            'amount_paid' => 'required|numeric',
            'payment_mode' => 'required|in:Cash,Mpesa,Bank',
        ]);

        $payment = FeePayment::findOrFail($id);
        $student = Student::findOrFail($request->student_id);

        $originalAmount = $payment->amount_paid;

        $payment->update([
            'class_id' => $request->class_id,
            'term_id' => $request->term_id,
            'student_id' => $request->student_id,
            'payment_mode' => $request->payment_mode,
            'amount_paid' => $request->amount_paid,
            'description' => $request->description,
            'receipt_number' => $request->receipt_number,
        ]);

        // Update student balance
        $difference = $request->amount_paid - $originalAmount;
        $student->current_balance -= $difference;
        $student->save();

        return redirect()->route('fee-payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(FeePayment $fee_payment)
    {
        $student = Student::find($fee_payment->student_id);
        $student->current_balance += $fee_payment->amount_paid;
        $student->save();

        $fee_payment->delete();

        return redirect()->back()->with('success', 'Payment deleted successfully!');
    }


}
