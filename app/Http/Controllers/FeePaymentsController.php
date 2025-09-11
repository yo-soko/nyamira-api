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
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;



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

        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('student_reg_number', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $query->latest()->paginate(10);

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

        $balances = [];

        foreach ($students as $student) {
            $levelId = $student->schoolClass->level_id ?? null;

            $totalFee = FeeStructure::where('level_id', $levelId)->sum('amount');
            $totalPaid = FeePayment::where('student_id', $student->id)->sum('amount_paid');

            $balances[$student->id] = $totalFee - $totalPaid; // can be negative (overpaid)
        }

        return view('fees.fee-payments', compact('payments', 'students', 'streams', 'classLevels', 'terms', 'balances'));

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
            'description' => 'required|string|in:Tuition Fee,Meals,Transport',
        ]);

        try {
            $dayPrefixes = [
                'Monday'    => 'M',
                'Tuesday'   => 'TU',
                'Wednesday' => 'W',
                'Thursday'  => 'TH',
                'Friday'    => 'F',
                'Saturday'  => 'SA',
                'Sunday'    => 'SU',
            ];

            $today = now()->format('l'); // Full weekday name (Monday, Tuesday...)
            $prefix = $dayPrefixes[$today] ?? 'X'; // fallback "X"

            // Get total receipts count so far (continuous sequence)
            $count = FeePayment::count() + 1;

            // Format as two digits (01, 02, 03...)
            $receiptNumber = 'RCPT-' . $prefix . str_pad($count, 2, '0', STR_PAD_LEFT);

            $payment = FeePayment::create([
                'class_id' => $validated['class_id'],
                'term_id' => $validated['term_id'],
                'student_id' => $validated['student_id'],
                'payment_mode' => $validated['payment_mode'],
                'amount_paid' => $validated['amount_paid'],
                'description' => $validated['description'],
                'receipt_number' =>  $receiptNumber,
                'user_id' => auth()->id(),
            ])->refresh();

            $student = Student::find($validated['student_id']);
            $student->current_balance -= $validated['amount_paid'];
            $student->save();

            return response()->json(['success' => true, 'payment_id' => $payment->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to record payment.']);
        }

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'term_id' => 'required|exists:terms,id',
            'student_id' => 'required|exists:students,id',
            
            'description' => 'nullable|string',
            'amount_paid' => 'required|numeric',
            'payment_mode' => 'required|in:Cash,Mpesa,Bank',
            'payment_for' => 'required|in:Tuition Fee,Meals,Transport',

        ]);

        $dayPrefixes = [
            'Monday'    => 'M',
            'Tuesday'   => 'TU',
            'Wednesday' => 'W',
            'Thursday'  => 'TH',
            'Friday'    => 'F',
            'Saturday'  => 'SA',
            'Sunday'    => 'SU',
        ];

        $today = now()->format('l'); // Full weekday name (Monday, Tuesday...)
        $prefix = $dayPrefixes[$today] ?? 'X'; // fallback "X"

        // Get total receipts count so far (continuous sequence)
        $count = FeePayment::count() + 1;

        // Format as two digits (01, 02, 03...)
        $receiptNumber = 'RCPT-' . $prefix . str_pad($count, 2, '0', STR_PAD_LEFT);

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
            'receipt_number' => $receiptNumber,
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

    public function dashboard(Request $request)
    {
        $year = $request->year;
        $termId = $request->term_id;

        $terms = Term::orderBy('year', 'desc')->get();
        $years = Term::distinct()->pluck('year');

        $currentTerm = Term::when($termId, fn($q) => $q->where('id', $termId))
            ->when(!$termId, fn($q) => $q->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now()))
            ->first();

        $termIds = $termId
            ? [$termId]
            : ($year ? Term::where('year', $year)->pluck('id') : Term::pluck('id'));

        $students = Student::with(['schoolClass.level', 'meal', 'transport'])->get();

        // --- Calculate Expected ---
        $expectedTuition = 0;
        $expectedMeal = 0;
        $expectedTransport = 0;

        foreach ($students as $student) {
            $levelId = $student->schoolClass->level_id ?? null;

            if ($levelId) {
                $expectedTuition += FeeStructure::where('level_id', $levelId)
                    ->when($termId, fn($q) => $q->where('term_id', $termId))
                    ->sum('amount');
            }

            if ($student->meal) {
                $expectedMeal += $student->meal->meal_fee ?? 0;
            }

            if ($student->transport) {
                $expectedTransport += $student->transport->transport_fee ?? 0;
            }
        }

        // --- Calculate Paid ---
        $actualPaidMeal = FeePayment::whereIn('term_id', $termIds)
            ->where('description', 'Meals')
            ->sum('amount_paid');

        $actualPaidTransport = FeePayment::whereIn('term_id', $termIds)
            ->where('description', 'Transport')
            ->sum('amount_paid');

        $actualPaidTuition = FeePayment::whereIn('term_id', $termIds)
            ->where('description', 'Tuition Fee')
            ->sum('amount_paid');


        // --- Combine ---
        $totalCollected = $actualPaidTuition + $actualPaidMeal + $actualPaidTransport;
        $outstandingBalance = Student::sum('current_balance');
        $totalStudents = $students->count();

        $recentPayments = FeePayment::with(['student.schoolClass.level'])->latest()->take(10)->get();

        // --- Per Level Breakdown ---
        $levels = ClassLevel::all();
        $levelData = [];

        foreach ($levels as $level) {
            $levelStudents = $students->filter(fn($s) => $s->schoolClass->level_id === $level->id);
            $expected = 0;
            $paid = 0;

            foreach ($levelStudents as $student) {
                $expected += FeeStructure::where('level_id', $level->id)
                    ->when($termId, fn($q) => $q->where('term_id', $termId))
                    ->sum('amount');

                $paid += FeePayment::where('student_id', $student->id)
                    ->whereIn('term_id', $termIds)
                    ->sum('amount_paid');
            }

            $levelData[] = [
                'name' => $level->level_name,
                'expected' => $expected,
                'paid' => $paid,
                'balance' => $expected - $paid
            ];
        }

        return view('fees.dashboard', compact(
            'terms', 'years', 'termId', 'year', 'currentTerm',
            'actualPaidTuition', 'actualPaidMeal', 'actualPaidTransport',
            'expectedTuition', 'expectedMeal', 'expectedTransport',
            'totalCollected', 'outstandingBalance', 'totalStudents',
            'recentPayments', 'levelData'
        ));
    }

    public function getPaymentOptions(Request $request)
    {
        return response()->json([
            'Tuition Fee',
            'Meals',
            'Transport'
        ]);
    }



    public function print(Request $request)
    {
        $data = $this->dashboard($request)->getData();
        return view('fees.partials.dashboard-print', (array) $data);
    }

    public function download(Request $request)
    {
        // Reuse dashboard logic
        $dashboard = $this->dashboard($request);

        $data = $dashboard->getData();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('fees.partials.dashboard-print', (array) $data);
            return $pdf->download('fee_dashboard.pdf');
        }

        if ($request->format === 'excel') {
            return Excel::download(new \App\Exports\FeeDashboardExport((array) $data), 'fee_dashboard.xlsx');
        }

        return back()->with('error', 'Invalid format selected.');
    }


    // public function printBalances()
    // {
    //     $students = \App\Models\Student::with(['schoolClass.level', 'schoolClass.stream'])
    //         ->where('current_balance', '>', 0)
    //         ->get();

    //     return view('fees.partials.print_balances', compact('students'));
    // }


  public function printBalances(Request $request)
{
    $query = Student::with(['schoolClass.level', 'schoolClass.stream', 'payments' => function ($q) {
            $q->latest()->limit(1);
        }]);

    // If a specific student is selected, prioritize that
    if ($request->filled('student_id')) {
        $query->where('id', $request->student_id);
    }
    // Else, allow filtering by class
    elseif ($request->filled('class_id')) {
        $query->where('class_id', $request->class_id);
    }

    // Only show those with balances
    $students = $query->where('current_balance', '>', 0)->get();

    // For dropdowns
    $classLevels = SchoolClass::with(['level', 'stream'])->get();

    return view('fees.partials.print_balances', compact('students', 'classLevels',));
}

// public function printFeeBalance(Request $request)
// {
//     $studentId = $request->student_id;

//     $student = Student::with('schoolClass.level', 'schoolClass.stream')
//                 ->findOrFail($studentId);

//     return view('fees.partials.print_balances', compact('student'));
// }

//     public function printFeeBalance(Request $request)
// {
//     $studentId = $request->student_id;

//     $student = Student::with('schoolClass.level', 'schoolClass.stream')
//                 ->findOrFail($studentId);

//     // 👇 Get the most recent payment
//     $recentPayment = FeePayment::where('student_id', $studentId)
//         ->orderByDesc('payment_id')   // make sure to use the correct PK
//         ->first();


//     return view('fees.partials.print_balances', compact('student', 'recentPayment'));
// }
public function printFeeBalance(Request $request)
{
    $studentIds = $request->student_id 
        ? [$request->student_id] 
        : Student::pluck('id')->toArray();

    $students = Student::with('schoolClass.level', 'schoolClass.stream')
                ->whereIn('id', $studentIds)
                ->get();

    // attach recent payment per student
    foreach ($students as $student) {
        $latestDate = FeePayment::where('student_id', $student->id)
                        ->max('created_at');

        $recentPayment = null;
        if ($latestDate) {
            $recentPayment = FeePayment::where('student_id', $student->id)
                                ->whereDate('created_at', \Carbon\Carbon::parse($latestDate)->toDateString())
                                ->sum('amount_paid');
        }
        $student->recent_payment = $recentPayment; // attach directly to model
    }

    return view('fees.partials.print_balances', compact('students'));
}

}