<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeePayment;

class SFeePaymentsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'student') {
            abort(403);
        }

        $student = $user->student;

        // Calculate total fee (structure + optional transport/meal etc.)
        $balance = $student->current_balance;

        // Filtered Payments
        $query = \App\Models\FeePayment::with('term')
            ->where('student_id', $student->id)
            ->latest();

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $payments = $query->paginate(10);
        $availableYears = \App\Models\FeePayment::selectRaw('YEAR(created_at) as year')
            ->where('student_id', $student->id)
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('students.fee-payments', compact('payments', 'availableYears', 'balance'));
    }

}

