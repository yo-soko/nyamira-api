<?php

namespace App\Http\Controllers;

use App\Models\WorkTicket;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WorkTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $collection = WorkTicket::with(['driver', 'vehicle'])->orderByDesc('travel_date')->get();

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        $user = auth()->user();

        // fetch only vehicles where operator_id = user_id
        $assignedVehicles = VehicleAssignment::where('operator_id', $user->id)->get();

        return view('work_tickets.index', [
            'tickets'  => $paginator,
            'vehicles' => $assignedVehicles,
            'users'    => User::all(),
            'vendors'    => Vendor::all(),
        ]);
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
        $user = auth()->user();

        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'travel_date' => 'required|date',
            'start_point' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'start_mileage' => 'required|integer',
            'estimated_distance' => 'nullable|string|max:255',
            'fuel_consumed' => 'nullable|numeric',
            'fuel_source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'passenger_names' => 'nullable|array',
            'passenger_numbers' => 'nullable|array',
        ]);

        // 🔹 Build passengers array
        $passengers = [];
        if (!empty($data['passenger_names'])) {
            foreach ($data['passenger_names'] as $i => $name) {
                if (trim($name) !== '') {
                    $passengers[] = [
                        'name' => $name,
                        'number' => $data['passenger_numbers'][$i] ?? null,
                    ];
                }
            }
        }

        // 🔹 Prepare final ticket data
        $ticketData = [
            'user_id'        => $user->id,
            'vehicle_id'     => $data['vehicle_id'],
            'travel_date'    => $data['travel_date'],
            'start_point'    => $data['start_point'],
            'end_point'      => $data['destination'],
            'purpose'        => $data['purpose'],
            'start_mileage'  => $data['start_mileage'],
            'notes'          => $data['notes'] ?? null,
            'fuel_used'      => $data['fuel_consumed'] ?? null,
            'fuel_source'    => $data['fuel_source'] ?? null,
            'department_id'  => $user->driver->department_id ?? null,
            'passengers'     => $passengers,
            'status'         => 'pending',
        ];

        // 🔹 Create work ticket
        WorkTicket::create($ticketData);

        return redirect()->back()->with('success', 'Work ticket submitted for approval.');
    }


    /**
     * Display the specified resource.
     */
    public function show(WorkTicket $workTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkTicket $workTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkTicket $workTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkTicket $workTicket)
    {
        //
    }
}
