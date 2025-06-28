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
        $todayAttendance = TransportAttendance::whereDate('date', today())->where('status', 'present')->count();
        $classes = SchoolClass::all();
        $terms = Term::all();
        $studentTransports = StudentTransport::with(['student', 'route', 'stop', 'class'])->get();
        $students = Student::with('class')->get(); // Added for student dropdown

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

    // Stop Management
    public function getStops($routeId)
    {
        $route = TransportRoute::findOrFail($routeId);
        $stops = $route->stops()->orderBy('stop_order')->get();
        return response()->json([
            'route' => $route,
            'stops' => $stops
        ]);
    }

    public function storeStop(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:transport_routes,id',
            'stop_name' => 'required|string|max:255',
            'pickup_time' => 'nullable|date_format:H:i',
            'dropoff_time' => 'nullable|date_format:H:i',
        ]);

        $lastStop = TransportStop::where('route_id', $validated['route_id'])
            ->orderBy('stop_order', 'desc')
            ->first();

        $validated['stop_order'] = $lastStop ? $lastStop->stop_order + 1 : 1;

        $stop = TransportStop::create($validated);
        return response()->json($stop);
    }

    public function updateStop(Request $request, $id)
    {
        $validated = $request->validate([
            'stop_name' => 'required|string|max:255',
            'pickup_time' => 'nullable|date_format:H:i',
            'dropoff_time' => 'nullable|date_format:H:i',
        ]);

        $stop = TransportStop::findOrFail($id);
        $stop->update($validated);
        return response()->json($stop);
    }

    public function destroyStop($id)
    {
        TransportStop::destroy($id);
        return response()->json(['success' => 'Stop deleted']);
    }

    // Vehicle Management
    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:50|unique:vehicles',
            'model' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1',
            'insurance_expiry' => 'nullable|date',
        ]);

        $vehicle = Vehicle::create($validated);
        return response()->json($vehicle);
    }

    public function editVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json($vehicle);
    }

    public function updateVehicle(Request $request, $id)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:50|unique:vehicles,registration_number,' . $id,
            'model' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1',
            'insurance_expiry' => 'nullable|date',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($validated);
        return response()->json($vehicle);
    }

    public function destroyVehicle($id)
    {
        Vehicle::destroy($id);
        return response()->json(['success' => 'Vehicle deleted']);
    }

    // Driver Management
    public function getDriver($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $driver = $vehicle->currentDriver;
        return response()->json([
            'driver' => $driver,
            'vehicle' => $vehicle
        ]);
    }

    public function assignDriver(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'assigned_from' => 'required|date',
            'assigned_to' => 'nullable|date|after:assigned_from',
        ]);

        DriverAssignment::where('vehicle_id', $validated['vehicle_id'])
            ->whereNull('assigned_to')
            ->update(['assigned_to' => now()]);

        $assignment = DriverAssignment::create($validated);
        return response()->json($assignment);
    }

    // Student Transport Management
    public function storeStudentTransport(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term_id' => 'required|exists:terms,id',
            'class_id' => 'required|exists:classes,id',
            'route_id' => 'required|exists:transport_routes,id',
            'stop_id' => 'nullable|exists:transport_stops,id',
            'transport_type' => 'required|in:one_way,two_way',
            'transport_fee' => 'required|numeric|min:0',
        ]);

        $validated['balance'] = $validated['transport_fee'];
        $transport = StudentTransport::create($validated);
        return response()->json($transport);
    }

    public function editStudentTransport($id)
    {
        $transport = StudentTransport::with(['student', 'route', 'stop', 'class'])->findOrFail($id);
        return response()->json($transport);
    }

    public function updateStudentTransport(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term_id' => 'required|exists:terms,id',
            'class_id' => 'required|exists:classes,id',
            'route_id' => 'required|exists:transport_routes,id',
            'stop_id' => 'nullable|exists:transport_stops,id',
            'transport_type' => 'required|in:one_way,two_way',
            'transport_fee' => 'required|numeric|min:0',
        ]);

        $transport = StudentTransport::findOrFail($id);

        if ($transport->transport_fee != $validated['transport_fee']) {
            $paid = $transport->transport_fee - $transport->balance;
            $validated['balance'] = $validated['transport_fee'] - $paid;
        }

        $transport->update($validated);
        return response()->json($transport);
    }

    public function destroyStudentTransport($id)
    {
        StudentTransport::destroy($id);
        return response()->json(['success' => 'Transport assignment removed']);
    }

    // Payment Management
    public function getPaymentInfo($id)
    {
        $transport = StudentTransport::with(['student', 'route'])->findOrFail($id);
        return response()->json($transport);
    }

    public function recordPayment(Request $request, $id)
    {
        $transport = StudentTransport::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $transport->balance,
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        TransportPayment::create([
            'student_transport_id' => $transport->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['reference'],
            'notes' => $validated['notes'],
        ]);

        $transport->decrement('balance', $validated['amount']);
        return response()->json($transport);
    }

    // Attendance Management
    // public function getAttendance(Request $request)
    // {
    //     $request->validate([
    //         'route_id' => 'required|exists:transport_routes,id',
    //         'date' => 'required|date',
    //     ]);

    //     $students = StudentTransport::with(['student', 'class', 'stop'])
    //         ->where('route_id', $request->route_id)
    //         ->get();

    //     $attendanceRecords = TransportAttendance::where('route_id', $request->route_id)
    //         ->whereDate('date', $request->date)
    //         ->get()
    //         ->keyBy('student_id');

    //     return response()->json([
    //         'students' => $students->map(function ($student) use ($attendanceRecords) {
    //             return [
    //                 'student' => $student->student,
    //                 'class' => $student->class,
    //                 'stop' => $student->stop,
    //                 'attendance' => $attendanceRecords[$student->student_id] ?? null
    //             ];
    //         })
    //     ]);
    // }

    // public function saveAttendance(Request $request)
    // {
    //     $request->validate([
    //         'route_id' => 'required|exists:transport_routes,id',
    //         'date' => 'required|date',
    //         'students' => 'required|array',
    //         'students.*.student_id' => 'required|exists:students,id',
    //         'students.*.status' => 'required|in:present,absent',
    //     ]);

    //     foreach ($request->students as $student) {
    //         TransportAttendance::updateOrCreate(
    //             [
    //                 'student_id' => $student['student_id'],
    //                 'route_id' => $request->route_id,
    //                 'date' => $request->date,
    //             ],
    //             [
    //                 'status' => $student['status'],
    //                 'marked_at' => now(),
    //             ]
    //         );
    //     }

    //     return response()->json(['success' => 'Attendance saved']);
    // }

    // Reports
    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:daily,monthly,payments,vehicle',
            'date' => 'required_if:type,daily|date',
            'month' => 'required_if:type,monthly|date_format:Y-m',
        ]);

        $reportType = $request->type;
        $results = [];

        switch ($reportType) {
            case 'daily':
                $date = $request->date;
                $results = TransportRoute::withCount([
                    'attendance as present' => function ($query) use ($date) {
                        $query->whereDate('date', $date)
                            ->where('status', 'present');
                    },
                    'attendance as absent' => function ($query) use ($date) {
                        $query->whereDate('date', $date)
                            ->where('status', 'absent');
                    }
                ])->get();
                break;

            case 'monthly':
                $month = $request->month;
                $results = TransportRoute::with(['attendance' => function ($query) use ($month) {
                    $query->whereMonth('date', Carbon::parse($month)->month)
                        ->whereYear('date', Carbon::parse($month)->year);
                }])->get()->map(function ($route) {
                    $attendance = $route->attendance->groupBy(function ($item) {
                        return $item->date->format('Y-m-d');
                    });

                    $dailyRates = $attendance->map(function ($day) {
                        $present = $day->where('status', 'present')->count();
                        $total = $day->count();
                        return [
                            'date' => $day->first()->date->format('Y-m-d'),
                            'rate' => $total > 0 ? round(($present / $total) * 100) : 0
                        ];
                    });

                    $bestDay = $dailyRates->sortByDesc('rate')->first();
                    $worstDay = $dailyRates->sortBy('rate')->first();

                    return [
                        'route_name' => $route->route_name,
                        'total_students' => StudentTransport::where('route_id', $route->id)->count(),
                        'avg_attendance' => $dailyRates->avg('rate') ?? 0,
                        'best_day' => $bestDay ?? ['date' => 'N/A', 'rate' => 0],
                        'worst_day' => $worstDay ?? ['date' => 'N/A', 'rate' => 0],
                    ];
                });
                break;

            case 'payments':
                $results = StudentTransport::with(['student', 'class', 'route'])
                    ->where('balance', '>', 0)
                    ->get();
                break;

            case 'vehicle':
                $results = Vehicle::with(['route', 'studentTransports'])
                    ->get()
                    ->map(function ($vehicle) {
                        $students = $vehicle->route ? $vehicle->route->studentTransports->count() : 0;
                        return [
                            'registration_number' => $vehicle->registration_number,
                            'model' => $vehicle->model,
                            'capacity' => $vehicle->capacity,
                            'route_name' => $vehicle->route ? $vehicle->route->route_name : 'Not Assigned',
                            'students' => $students,
                        ];
                    });
                break;
        }

        return response()->json($results);
    }

    public function exportReport(Request $request)
    {
        $report = $this->generateReport($request)->getData();
        $type = $request->type;
        $date = $type === 'monthly' ? $request->month : $request->date;

        $pdf = PDF::loadView('pdf.transport-report', [
            'report' => $report,
            'type' => $type,
            'date' => $date
        ]);

        return $pdf->download("transport-report-{$type}-{$date}.pdf");
    }

    public function getDashboardCounters()
    {
        return response()->json([
            'activeRoutes' => TransportRoute::where('status', true)->count(),
            'studentCount' => StudentTransport::count(),
            'availableVehicles' => Vehicle::count(),
            'todayAttendance' => TransportAttendance::whereDate('date', today())->where('status', 'present')->count()
        ]);
    }

    public function getInitialData()
    {
        return response()->json([
            'classes' => SchoolClass::all(),
            'terms' => Term::all(),
            'students' => Student::with('class')->get()
        ]);
    }
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

            // Eager-load relevant transport records
            $transports = StudentTransport::with([
                'student:id,first_name,last_name,class_id',
                'student.class:id', // Avoid requesting `name` if it doesn't exist
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
                $attendance = $student->transportAttendances->first();

                return [
                    'id' => $student->id,
                    'full_name' => $student->first_name . ' ' . $student->last_name,
                    'class_id' => $student->class_id, // Since no class name field exists
                    'stop' => $transport->stop ? [
                        'stop_name' => $transport->stop->stop_name
                    ] : null,
                    'attendance' => $attendance ? [
                        'id' => $attendance->id,
                        'status' => $attendance->status ?? 'absent',
                        'pickup_time' => $attendance->pickup_time,
                        'dropoff_time' => $attendance->dropoff_time,
                    ] : null
                ];
            })->filter()->values(); // Clean nulls + reindex

            $stops = TransportStop::where('route_id', $routeId)->get(['id', 'stop_name']);

            return response()->json([
                'students' => $students,
                'stops' => $stops
            ]);
        } catch (\Throwable $e) {
            Log::error('Attendance loading error', [
                'error' => $e->getMessage(),
                'route_id' => $request->route_id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Failed to load attendance data',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function saveAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'route_id' => 'required|exists:transport_routes,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'session_type' => 'required|in:pickup,dropoff'
        ]);

        try {
            $studentId = $request->student_id;
            $routeId = $request->route_id;
            $date = $request->date;
            $status = $request->status;
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
                'date' => $date,
            ]);

            $attendance->route_id = $routeId; // Optional, depending on schema
            $attendance->status = $status;
            $attendance->marked_at = now();

            if ($sessionType === 'pickup') {
                $attendance->pickup_time = now()->format('H:i:s');
                $attendance->pickup_location = $transport->stop->stop_name ?? null;
            } else {
                $attendance->dropoff_time = now()->format('H:i:s');
                $attendance->dropoff_location = $transport->stop->stop_name ?? null;
            }

            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'attendance_id' => $attendance->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Attendance saving error', [
                'student_id' => $request->student_id,
                'route_id' => $request->route_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to record attendance',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
