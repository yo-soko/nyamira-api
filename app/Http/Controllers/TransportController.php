<?php

namespace App\Http\Controllers;

use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\Vehicle;
use App\Models\DriverAssignment;
use App\Models\StudentTransport;
use App\Models\TransportAttendance;
use App\Models\Student;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\TransportPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class TransportController extends Controller
{
    public function index()
    {
        $routes = TransportRoute::with(['stops', 'vehicle'])->get();
        $vehicles = Vehicle::with('route')->get();
        $studentCount = StudentTransport::count();

        // Calculate today's attendance based on current session
        $currentSession = (now()->hour < 12) ? 'pickup' : 'dropoff';
        $todayAttendance = TransportAttendance::whereDate('date', today())
            ->where(function ($query) use ($currentSession) {
                if ($currentSession === 'pickup') {
                    $query->whereNotNull('pickup_status')->where('pickup_status', 'present');
                } else {
                    $query->whereNotNull('dropoff_status')->where('dropoff_status', 'present');
                }
            })
            ->count();

        $classes = SchoolClass::all();
        $terms = Term::all();
        $studentTransports = StudentTransport::with(['student', 'route', 'stop', 'class'])->get();
        $students = Student::with('class')->get();

        return view('transport', compact(
            'routes',
            'vehicles',
            'studentCount',
            'todayAttendance',
            'classes',
            'terms',
            'studentTransports',
            'students'
        ));
    }

    // Route CRUD Operations
    public function storeRoute(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|date_format:H:i',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'status' => 'required|boolean',
        ]);

        $route = TransportRoute::create($validated);
        return response()->json($route);
    }

    public function editRoute($id)
    {
        $route = TransportRoute::findOrFail($id);
        return response()->json($route);
    }

    public function updateRoute(Request $request, $id)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|date_format:H:i',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'status' => 'required|boolean',
        ]);

        $route = TransportRoute::findOrFail($id);
        $route->update($validated);
        return response()->json($route);
    }

    public function destroyRoute($id)
    {
        TransportRoute::destroy($id);
        return response()->json(['success' => 'Route deleted']);
    }

    // Attendance Management
    public function getAttendance(Request $request)
    {
        try {
            $request->validate([
                'route_id' => 'required|exists:transport_routes,id',
                'date' => 'nullable|date',
                'session_type' => 'required|in:pickup,dropoff'
            ]);

            $routeId = $request->route_id;
            $date = $request->date ?: now()->toDateString();
            $sessionType = $request->session_type;

            $transports = StudentTransport::with([
                'student:id,first_name,last_name,class_id',
                'student.class.level:id,level_name',
                'student.class.stream:id,name',
                'stop:id,stop_name',
                'student.transportAttendances' => function ($query) use ($date) {
                    $query->whereDate('date', $date);
                }
            ])
                ->where('route_id', $routeId)
                ->get();

            $students = $transports->map(function ($transport) use ($sessionType) {
                if (!$transport->student) return null;

                $student = $transport->student;
                $class = $student->class;
                $attendance = $student->transportAttendances->first();

                return [
                    'id' => $student->id,
                    'full_name' => "{$student->first_name} {$student->last_name}",
                    'class_name' => trim(
                        (optional($class->level)->level_name ?? '') . ' ' .
                            (optional($class->stream)->name ?? '')
                    ) ?: 'N/A',
                    'stop' => $transport->stop,
                    'attendance' => $attendance ? [
                        'id' => $attendance->id,
                        'pickup_status' => $attendance->pickup_status,
                        'dropoff_status' => $attendance->dropoff_status,
                        'pickup_time' => $attendance->pickup_time,
                        'dropoff_time' => $attendance->dropoff_time,
                        'pickup_location' => $attendance->pickup_location,
                        'dropoff_location' => $attendance->dropoff_location
                    ] : null
                ];
            })->filter();

            return response()->json([
                'students' => $students,
                'current_session' => $sessionType
            ]);
        } catch (\Throwable $e) {
            Log::error('Attendance loading error', [
                'error' => $e->getMessage(),
                'route_id' => $request->route_id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Failed to load attendance data'
            ], 500);
        }
    }

    public function saveAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'route_id' => 'required|exists:transport_routes,id',
            'date' => 'required|date',
            'session_type' => 'required|in:pickup,dropoff',
            'pickup_status' => 'nullable|in:present,absent',
            'dropoff_status' => 'nullable|in:present,absent',
            'pickup_location' => 'nullable|string',
            'dropoff_location' => 'nullable|string',
        ]);

        try {
            $studentId = $request->student_id;
            $routeId = $request->route_id;
            $date = $request->date;
            $sessionType = $request->session_type;

            $transport = StudentTransport::with('stop')
                ->where('student_id', $studentId)
                ->where('route_id', $routeId)
                ->first();

            if (!$transport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student transport record not found for given route'
                ], 404);
            }

            $attendance = TransportAttendance::firstOrNew([
                'student_id' => $studentId,
                'route_id' => $routeId,
                'date' => $date,
            ]);

            if ($sessionType === 'pickup') {
                $attendance->pickup_status = $request->pickup_status;
                $attendance->pickup_time = now()->format('H:i:s');
                $attendance->pickup_location = $request->pickup_location ?? ($transport->stop->stop_name ?? 'School');
            } else {
                $attendance->dropoff_status = $request->dropoff_status;
                $attendance->dropoff_time = now()->format('H:i:s');
                $attendance->dropoff_location = $request->dropoff_location ?? ($transport->stop->stop_name ?? 'School');
            }

            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            Log::error('Attendance saving error', [
                'student_id' => $request->student_id,
                'route_id' => $request->route_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to record attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateReport(Request $request)
    {
        // Handle initial empty view (GET visit without filters)
        if (!$request->has(['type', 'month'])) {
            return view('transport-reports', [
                'reportType' => null,
                'month' => null,
                'routeId' => null,
                'routes' => TransportRoute::all(),
                'reportData' => collect(),
            ]);
        }

        // Validate only when filters are submitted
        $request->validate([
            'type' => 'required|in:attendance,billing',
            'route_id' => 'nullable|exists:transport_routes,id',
            'month' => 'required|date_format:Y-m',
        ]);

        try {
            $reportType = $request->type;
            $routeId = $request->route_id;
            $month = Carbon::parse($request->month);
            $startDate = $month->copy()->startOfMonth();
            $endDate = $month->copy()->endOfMonth();

            $data = [];

            if ($reportType === 'attendance') {
                $query = TransportAttendance::with(['student', 'route', 'student.class'])
                    ->whereBetween('date', [$startDate, $endDate]);

                if ($routeId) {
                    $query->where('route_id', $routeId);
                }

                $data = $query->get()
                    ->groupBy('student_id')
                    ->map(function ($records) {
                        $student = $records->first()->student;
                        $presentDays = $records->filter(fn($r) =>
                        $r->pickup_status === 'present' || $r->dropoff_status === 'present')->count();
                        $absentDays = $records->filter(fn($r) =>
                        $r->pickup_status === 'absent' || $r->dropoff_status === 'absent')->count();
                        $totalDays = $presentDays + $absentDays;
                        $rate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

                        return [
                            'student' => $student->full_name,
                            'class' => optional($student->class)->name,
                            'route' => $records->first()->route->route_name,
                            'stop' => optional($student->transport->stop)->stop_name ?? 'N/A',
                            'present_days' => $presentDays,
                            'absent_days' => $absentDays,
                            'attendance_rate' => $rate . '%',
                        ];
                    })->values();
            } else {
                // Billing Report
                $query = StudentTransport::with(['student', 'route', 'stop', 'payments'])
                    ->whereHas('route')
                    ->when($routeId, fn($q) => $q->where('route_id', $routeId));

                $data = $query->get()
                    ->map(function ($transport) use ($month) {
                        $payments = $transport->payments()
                            ->whereMonth('payment_date', $month)
                            ->sum('amount');

                        $fee = $transport->transport_fee ?? 0;

                        return [
                            'student' => $transport->student->full_name,
                            'class' => optional($transport->student->class)->name ?? 'N/A',
                            'route' => $transport->route->route_name,
                            'fee' => $fee,
                            'paid' => $payments,
                            'balance' => $fee - $payments,
                            'status' => ($fee - $payments) <= 0 ? 'Paid' : 'Pending',
                        ];
                    });
            }

            // ✅ AJAX Request → return JSON
            if ($request->ajax()) {
                return response()->json($data);
            }

            // ✅ Normal Request → return Blade view
            return view('transport-reports', [
                'reportType' => $reportType,
                'month' => $month->format('Y-m'),
                'routeId' => $routeId,
                'routes' => TransportRoute::all(),
                'reportData' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Report generation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to generate report: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors('Failed to generate report: ' . $e->getMessage());
        }
    }

    public function updateAttendance(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'route_id' => 'required|exists:transport_routes,id',
            'date' => 'required|date',
            'session_type' => 'required|in:pickup,dropoff',
            'pickup_status' => 'nullable|in:present,absent',
            'dropoff_status' => 'nullable|in:present,absent',
        ]);

        try {
            $attendance = TransportAttendance::findOrFail($id);

            if ($request->session_type === 'pickup') {
                $attendance->pickup_status = $request->pickup_status;
                $attendance->pickup_time = now()->format('H:i:s');
                $attendance->pickup_location = $request->pickup_location ?? $attendance->pickup_location;
            } else {
                $attendance->dropoff_status = $request->dropoff_status;
                $attendance->dropoff_time = now()->format('H:i:s');
                $attendance->dropoff_location = $request->dropoff_location ?? $attendance->dropoff_location;
            }

            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'attendance' => $attendance
            ]);
        } catch (\Exception $e) {
            Log::error('Attendance update error', [
                'attendance_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update attendance'
            ], 500);
        }
    }

    public function report(Request $request)
    {
        // Handle initial empty view (GET visit without filters)
        if (!$request->has(['type', 'month'])) {
            return view('transport-reports', [
                'reportType' => null,
                'month' => null,
                'routeId' => null,
                'routes' => TransportRoute::all(),
                'reportData' => collect(),
            ]);
        }

        // Validate only when filters are submitted
        $request->validate([
            'type' => 'required|in:attendance,billing',
            'route_id' => 'nullable|exists:transport_routes,id',
            'month' => 'required|date_format:Y-m',
        ]);

        try {
            $reportType = $request->type;
            $routeId = $request->route_id;
            $month = Carbon::parse($request->month);
            $startDate = $month->copy()->startOfMonth();
            $endDate = $month->copy()->endOfMonth();

            $data = [];

            if ($reportType === 'attendance') {
                $query = TransportAttendance::with(['student', 'route', 'student.class'])
                    ->whereBetween('date', [$startDate, $endDate]);

                if ($routeId) {
                    $query->where('route_id', $routeId);
                }

                $data = $query->get()
                    ->groupBy('student_id')
                    ->map(function ($records) {
                        $student = $records->first()->student;
                        $presentDays = $records->filter(fn($r) =>
                        $r->pickup_status === 'present' || $r->dropoff_status === 'present')->count();
                        $absentDays = $records->filter(fn($r) =>
                        $r->pickup_status === 'absent' || $r->dropoff_status === 'absent')->count();
                        $totalDays = $presentDays + $absentDays;
                        $rate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

                        return [
                            'student' => $student->full_name,
                            'class' => optional($student->class)->name ?? 'N/A',
                            'route' => $records->first()->route->route_name,
                            'present_days' => $presentDays,
                            'absent_days' => $absentDays,
                            'attendance_rate' => $rate . '%',
                        ];
                    })->values();
            } else {
                // Billing Report
                $query = StudentTransport::with(['student', 'route', 'stop', 'payments'])
                    ->whereHas('route')
                    ->when($routeId, fn($q) => $q->where('route_id', $routeId));

                $data = $query->get()
                    ->map(function ($transport) use ($month) {
                        $payments = $transport->payments()
                            ->whereMonth('payment_date', $month)
                            ->sum('amount');

                        $fee = $transport->transport_fee ?? 0;

                        return [
                            'student' => $transport->student->full_name,
                            'class' => optional($transport->student->class)->name ?? 'N/A',
                            'route' => $transport->route->route_name,
                            'fee' => $fee,
                            'paid' => $payments,
                            'balance' => $fee - $payments,
                            'status' => ($fee - $payments) <= 0 ? 'Paid' : 'Pending',
                        ];
                    });
            }

            // ✅ If AJAX request, return raw data
            if ($request->ajax()) {
                return response()->json($data);
            }

            // ✅ Otherwise, render view
            return view('transport-reports', [
                'reportType' => $reportType,
                'month' => $month->format('Y-m'),
                'routeId' => $routeId,
                'routes' => TransportRoute::all(),
                'reportData' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Report generation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to generate report: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors('Failed to generate report: ' . $e->getMessage());
        }
    }

    public function exportReport(Request $request)
    {
        $type = $request->input('type');
        $routeId = $request->input('route_id');
        $month = $request->input('month');
        $date = $request->input('date') ?? now()->format('Y-m-d');

        $query = TransportAttendance::with(['student', 'route']);

        // Handle filtering
        if ($type === 'pickup') {
            $query->whereDate('date', $date)->whereNotNull('pickup_status');
        } elseif ($type === 'dropoff') {
            $query->whereDate('date', $date)->whereNotNull('dropoff_status');
        } elseif ($type === 'attendance') {
            $query->whereDate('date', $date);
        } elseif ($type === 'daily') {
            // Build daily summary (grouped by route)
            $raw = TransportAttendance::whereDate('date', $date)->with('route')->get();
            $report = $raw->groupBy('route.route_name')->map(function ($records, $routeName) {
                $present = $records->filter(fn($r) => $r->pickup_status === 'present' || $r->dropoff_status === 'present')->count();
                $absent = $records->filter(fn($r) => $r->pickup_status === 'absent' && $r->dropoff_status === 'absent')->count();
                return [
                    'route_name' => $routeName,
                    'present' => $present,
                    'absent' => $absent,
                ];
            })->values();
            return Pdf::loadView('pdf.transport-report', compact('type', 'date', 'report'))->download("transport_report_{$type}_{$date}.pdf");
        } elseif ($type === 'monthly' && $month) {
            [$year, $monthNum] = explode('-', $month);
            $raw = TransportAttendance::whereYear('date', $year)->whereMonth('date', $monthNum)->with('route')->get();
            $report = $raw->groupBy('route.route_name')->map(function ($records, $routeName) {
                $total = $records->count();
                $presentCount = $records->filter(fn($r) => $r->pickup_status === 'present' || $r->dropoff_status === 'present')->count();
                $avg = $total > 0 ? round(($presentCount / $total) * 100) : 0;

                $byDay = $records->groupBy(fn($r) => $r->date->format('Y-m-d'));
                $dailyRates = $byDay->map(function ($dayRecords) {
                    $dayTotal = $dayRecords->count();
                    $present = $dayRecords->filter(fn($r) => $r->pickup_status === 'present' || $r->dropoff_status === 'present')->count();
                    return [
                        'date' => $dayRecords->first()->date->format('Y-m-d'),
                        'rate' => $dayTotal > 0 ? round(($present / $dayTotal) * 100) : 0,
                    ];
                });

                return [
                    'route_name' => $routeName,
                    'total_students' => $total,
                    'avg_attendance' => $avg,
                    'best_day' => $dailyRates->sortByDesc('rate')->first(),
                    'worst_day' => $dailyRates->sortBy('rate')->first(),
                ];
            })->values();

            return Pdf::loadView('pdf.transport-report', [
                'type' => $type,
                'date' => $month,
                'report' => $report,
            ])->download("transport_report_{$type}_{$month}.pdf");
        }

        // Default query if above does not return early
        if ($routeId) {
            $query->where('route_id', $routeId);
        }

        $report = $query->get();

        if ($report->isEmpty()) {
            return back()->with('error', 'No records found for this report.');
        }

        return Pdf::loadView('pdf.transport-report', [
            'type' => $type,
            'date' => $date,
            'report' => $report,
        ])->download("transport_report_{$type}_" . now()->format('Ymd_His') . ".pdf");
    }
}
