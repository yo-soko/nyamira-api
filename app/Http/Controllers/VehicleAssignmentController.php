<?php

namespace App\Http\Controllers;

use App\Models\VehicleAssignment;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = VehicleAssignment::with(['vehicle', 'operator'])->get();
        $vehicles = Vehicle::all();
        $operators= User::where('role', 'driver')->where('status', 1)->get();


        return view('assignments.create', compact('assignments', 'vehicles', 'operators'));
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        $vehicles = Vehicle::all();
        $operators = User::all();
        return view('assignments.create', compact('vehicles', 'operators'));
    }

    /**
     * Store a newly created resource in storage.
     */
   

    /**
     * Display the specified resource.
     */
    public function show(Vehicle_assignment $vehicle_assignment)
    {
        //
    } public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'operator_id' => 'required|exists:users,id',
            'assignment_type' => 'required|string|max:255',
            'assignment_location' => 'nullable|string|max:255',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);
        if (!empty($data['start_at'])) {
            $data['start_at'] = \Carbon\Carbon::parse($data['start_at'])->format('Y-m-d H:i:s');
        }
        if (!empty($data['end_at'])) {
            $data['end_at'] = \Carbon\Carbon::parse($data['end_at'])->format('Y-m-d H:i:s');
        }

        VehicleAssignment::create($data);

        return redirect()
            ->route('assignments.index')
            ->with('success', 'Vehicle assigned successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle_assignment $vehicle_assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle_assignment $vehicle_assignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle_assignment $vehicle_assignment)
    {
        //
    }
}
