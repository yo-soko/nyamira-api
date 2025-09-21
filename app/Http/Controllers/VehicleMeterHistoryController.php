<?php

namespace App\Http\Controllers;

use App\Models\VehicleMeterHistory;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;

class VehicleMeterHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = VehicleMeterHistory::with(['vehicle', 'operator'])
        ->latest('recorded_at')
        ->paginate(10);

        $vehicles = Vehicle::orderBy('name')->get();

        return view('meter_histories.index', compact('histories', 'vehicles'));
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
        $data = $request->validate([
            'vehicle_id'    => 'required|exists:vehicles,id',
            'meter_reading' => 'required|numeric|min:0',
            'source'        => 'required|in:manual,gps',
            'recorded_at'   => 'nullable|date',
        ]);

        $data['recorded_at'] = $data['recorded_at'] ?? now();

        // Find assignment during that time
        $assignment = VehicleAssignment::where('vehicle_id', $data['vehicle_id'])
            ->where(function ($q) use ($data) {
                $q->whereNull('end_at')
                ->orWhere(function ($q2) use ($data) {
                    $q2->where('start_at', '<=', $data['recorded_at'])
                        ->where('end_at', '>=', $data['recorded_at']);
                });
            })
            ->latest('start_at')
            ->first();

        if ($assignment) {
            $data['operator_id'] = $assignment->operator_id;
        }

        VehicleMeterHistory::create($data);

        return back()->with('success', 'Meter reading saved successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(VehicleMeterHistory $vehicleMeterHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleMeterHistory $vehicleMeterHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleMeterHistory $vehicleMeterHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleMeterHistory $vehicleMeterHistory)
    {
        //
    }
}
