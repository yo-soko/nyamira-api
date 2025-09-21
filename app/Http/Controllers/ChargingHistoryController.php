<?php

namespace App\Http\Controllers;

use App\Models\ChargingHistory;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class ChargingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chargingHistories = ChargingHistory::with(['vehicle','vendor'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $vendors = User::all();

        return view('charging_histories.index', compact('chargingHistories','vehicles','vendors'));
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
            'vendor_id' => 'nullable',
            'odometer' => 'nullable|integer|min:0',
            'charging_started' => 'required|date',
            'charging_ended' => 'nullable|date|after_or_equal:charging_started',
            'total_energy' => 'nullable|numeric|min:0',
            'energy_price' => 'nullable|numeric|min:0',
            'energy_cost' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'is_personal' => 'boolean',
            'comments' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,png,jpg|max:4096',
        ]);

        // Auto-calculate duration & cost if not provided
        if (!empty($data['charging_started']) && !empty($data['charging_ended'])) {
            $data['charging_duration'] = now()->parse($data['charging_started'])
                ->diffInMinutes(now()->parse($data['charging_ended']));
        }

        if (!empty($data['total_energy']) && !empty($data['energy_price'])) {
            $data['energy_cost'] = $data['total_energy'] * $data['energy_price'];
        }

        // Handle uploads
        if ($request->hasFile('photos')) {
            $data['photos'] = array_map(fn($f) => $f->store('charging/photos','public'), $request->file('photos'));
        }
        if ($request->hasFile('documents')) {
            $data['documents'] = array_map(fn($f) => $f->store('charging/documents','public'), $request->file('documents'));
        }

        ChargingHistory::create($data);

        return redirect()->route('charging_histories.index')->with('success','Charging entry saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChargingHistory $chargingHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChargingHistory $chargingHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChargingHistory $chargingHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChargingHistory $chargingHistory)
    {
        //
    }
}
