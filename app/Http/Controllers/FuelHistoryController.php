<?php

namespace App\Http\Controllers;

use App\Models\FuelHistory;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class FuelHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fuelHistories = FuelHistory::with(['vehicle', 'vendor'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $vendors = User::all();

        return view('fuel_histories.index', compact('fuelHistories', 'vehicles', 'vendors'));
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
            'vehicle_id' => 'required|exists:vehicles,id',
            'fuel_entry_date' => 'required|date',
            'vendor_id' => 'nullable',
            'reference' => 'nullable|string|max:255',
            'is_personal' => 'boolean',
            'is_partial' => 'boolean',
            'reset_usage' => 'boolean',
            'comments' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,png,jpg|max:4096',
        ]);

        // Handle file uploads
        if ($request->hasFile('photos')) {
            $data['photos'] = array_map(fn($file) => $file->store('fuel/photos', 'public'), $request->file('photos'));
        }
        if ($request->hasFile('documents')) {
            $data['documents'] = array_map(fn($file) => $file->store('fuel/documents', 'public'), $request->file('documents'));
        }

        FuelHistory::create($data);

        return redirect()->route('fuel_histories.index')->with('success', 'Fuel entry saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FuelHistory $fuelHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FuelHistory $fuelHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FuelHistory $fuelHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FuelHistory $fuelHistory)
    {
        //
    }
}
