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
use PDF;
use Illuminate\Support\Facades\Log;

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

            if ($reportType === 'attendance') {
                $query = TransportAttendance::with(['student', 'route', 'student.class'])
                    ->whereBetween('date', [$startDate, $endDate]);

                if ($routeId) {
                    $query->where('route_id', $routeId);
                }

                $attendances = $query->get()
                    ->groupBy('student_id')
                    ->map(function ($records, $studentId) {
                        $student = $records->first()->student;
                        $presentDays = $records->filter(function ($record) {
                            return $record->pickup_status === 'present' ||
                                $record->dropoff_status === 'present';
                        })->count();

                        $absentDays = $records->filter(function ($record) {
                            return $record->pickup_status === 'absent' ||
                                $record->dropoff_status === 'absent';
                        })->count();

                        $totalDays = $presentDays + $absentDays;
                        $rate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;

                        return [
                            'student' => $student->full_name,
                            'class' => optional($student->class)->name ?? 'N/A',
                            'route' => $records->first()->route->route_name,
                            'stop' => optional($student->transport->stop)->stop_name ?? 'N/A',
                            'present_days' => $presentDays,
                            'absent_days' => $absentDays,
                            'attendance_rate' => $rate . '%'
                        ];
                    })->values();

                return response()->json($attendances);
            } else {
                // Billing report
                $query = StudentTransport::with(['student', 'route', 'stop', 'payments'])
                    ->whereHas('route')
                    ->when($routeId, function ($query) use ($routeId) {
                        return $query->where('route_id', $routeId);
                    });

                $transports = $query->get()
                    ->map(function ($transport) use ($month) {
                        $payments = $transport->payments()
                            ->whereMonth('payment_date', $month)
                            ->sum('amount');

                        return [
                            'student' => $transport->student->full_name,
                            'class' => optional($transport->student->class)->name ?? 'N/A',
                            'route' => $transport->route->route_name,
                            'fee' => $transport->transport_fee ?? 0,
                            'paid' => $payments,
                            'balance' => ($transport->transport_fee ?? 0) - $payments,
                            'status' => (($transport->transport_fee ?? 0) - $payments) <= 0 ? 'Paid' : 'Pending'
                        ];
                    });

                return response()->json($transports);
            }
        } catch (\Exception $e) {
            Log::error('Report generation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
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


    public function exportReport(Request $request)
    {
        $report = $this->generateReport($request)->getData();
        $type = $request->type;
        $month = $request->month;

        $pdf = PDF::loadView('pdf.transport-report', [
            'report' => $report,
            'type' => $type,
            'month' => $month
        ]);

        return $pdf->download("transport-{$type}-report-{$month}.pdf");
    }
}
