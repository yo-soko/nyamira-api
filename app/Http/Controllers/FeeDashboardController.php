<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeePayment;
use App\Models\Student;
use App\Models\ClassLevel;
use App\Models\Term;
use App\Models\FeeStructure;

class FeeDashboardController extends Controller
{
    public function index()
    {
        $totalCollected = FeePayment::sum('amount_paid');
        $outstandingBalance = Student::sum('current_balance');
        $totalStudents = Student::count();
        $recentPayments = FeePayment::with(['student.schoolClass.level'])->latest()->take(10)->get();

        return view('fees.dashboard', compact(
            'totalCollected',
            'outstandingBalance',
            'totalStudents',
            'recentPayments'
        ));
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

        // Expected fees
        $expectedTuition = 0;
        $expectedMeal = 0;
        $expectedTransport = 0;

        foreach ($students as $student) {
            $levelId = $student->schoolClass->level_id ?? null;

            if ($levelId) {
                $expectedTuition += FeeStructure::where('level_id', $levelId)
                    ->whereIn('term_id', $termIds)
                    ->sum('amount');
            }

            if ($student->meal) {
                $expectedMeal += $student->meal->meal_fee ?? 0;
            }

            if ($student->transport) {
                $expectedTransport += $student->transport->transport_fee ?? 0;
            }
        }

        // Actual Paid
        $actualPaidMeal = FeePayment::whereIn('term_id', $termIds)
            ->where('description', 'Meals')
            ->sum('amount_paid');

        $actualPaidTransport = FeePayment::whereIn('term_id', $termIds)
            ->where('description', 'Transport')
            ->sum('amount_paid');

        $actualPaidTuition = FeePayment::whereIn('term_id', $termIds)
            ->where('description', 'Tuition Fee')
            ->sum('amount_paid');


        $expectedTotal = $expectedTuition + $expectedMeal + $expectedTransport;
        $actualPaidTotal = $actualPaidTuition + $actualPaidMeal + $actualPaidTransport;
        $outstandingBalance = $expectedTotal - $actualPaidTotal;
        $totalCollected = $actualPaidTotal;

        $totalStudents = $students->count();

        // Recent
        $recentPayments = FeePayment::latest()->take(10)->get();

        // Breakdown by level
        $levels = ClassLevel::all();
        $levelData = [];

        foreach ($levels as $level) {
            $studentsInLevel = $students->filter(fn($s) => $s->schoolClass->level_id === $level->id);

            $levelExpected = FeeStructure::where('level_id', $level->id)
                ->whereIn('term_id', $termIds)
                ->sum('amount') * $studentsInLevel->count();

            $levelPaid = FeePayment::whereIn('term_id', $termIds)
                ->whereIn('student_id', $studentsInLevel->pluck('id'))
                ->sum('amount_paid');

            $levelData[] = [
                'name' => $level->level_name,
                'expected' => $levelExpected,
                'paid' => $levelPaid,
                'balance' => $levelExpected - $levelPaid
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

}
