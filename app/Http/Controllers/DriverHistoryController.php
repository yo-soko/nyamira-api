<?php

namespace App\Http\Controllers;

use App\Models\DriverHistory;
use App\Models\User;
use App\Models\VehicleAssignment;
use App\Models\VehicleMeterHistory;
use Illuminate\Http\Request;

class DriverHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = User::where('role', 'driver')->where('status', 1)->get();
        return view('driver-history.index', compact('drivers'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($driverId)
    {
        $driver = User::findOrFail($driverId);

        // Assignments history
        $assignments = VehicleAssignment::with('vehicle')
            ->where('operator_id', $driver->id)
            ->orderByDesc('start_at')
            ->get();

        // Meter readings
        $meters = VehicleMeterHistory::with('vehicle')
            ->where('operator_id', $driver->id)
            ->orderByDesc('recorded_at')
            ->get();

        // If you want to add a third source (example: issues table)
        // $issues = Issue::with('vehicle')->where('operator_id', $driver->id)->get();

        return view('driver-history.show', compact('driver', 'assignments', 'meters'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DriverHistory $driverHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DriverHistory $driverHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DriverHistory $driverHistory)
    {
        //
    }
}
