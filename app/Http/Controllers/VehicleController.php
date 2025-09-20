<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
     {
        // Fetch vehicles with their assigned operator (user)
        $vehicles = Vehicle::with('operator')->latest()->paginate(10);

        // Fetch users (for assigning operators in the form)
        $users = User::where('status', true)->get();

        return view('vehicles.create', compact('vehicles', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // drivers/operators
        return view('vehicles.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'vin' => 'nullable|string|max:255',
        'license_plate' => 'required|string|max:50|unique:vehicles',
        'type' => 'nullable|string|max:100',
        'fuel_type' => 'nullable|string|max:100',
        'year' => 'nullable|digits:4|integer',
        'make' => 'nullable|string|max:100',
        'model' => 'nullable|string|max:100',
        'trim' => 'nullable|string|max:100',
        'registration_state' => 'nullable|string|max:100',
        'status' => 'required|string|max:50',
        'group' => 'nullable|string|max:100',
        'operator_id' => 'nullable|exists:users,id',
        'ownership' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:50',
        'body_type' => 'nullable|string|max:100',
        'body_subtype' => 'nullable|string|max:100',
        'msrp' => 'nullable|numeric',
        'labels' => 'nullable|string', // will be converted
        'purchase_date' => 'nullable|date',
        'purchase_price' => 'nullable|numeric',
        'retirement_date' => 'nullable|date',
        'insurance_policy_number' => 'nullable|string|max:255',
        'insurance_expiry' => 'nullable|date',
        'loan_details' => 'nullable|string',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Handle file upload
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('vehicles', 'public');
    }

    // Convert labels string → JSON array
    if (!empty($validated['labels'])) {
        $validated['labels'] = array_map('trim', explode(',', $validated['labels']));
    }

    Vehicle::create($validated);

    return redirect()
        ->route('vehicles.index')
        ->with('success', 'Vehicle registered successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vehicle $vehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(vehicle $vehicle)
    {
        //
    }
}
