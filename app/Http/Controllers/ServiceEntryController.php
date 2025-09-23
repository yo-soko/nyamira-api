<?php

namespace App\Http\Controllers;

use App\Models\ServiceEntry;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Issues;
use Illuminate\Http\Request;

class ServiceEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = ServiceEntry::with(['vehicle', 'vendor', 'issues'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $vendors = User::all();
        return view('services.index', compact('services','vehicles', 'vendors'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $vendors = User::all();
        return view('services.create', compact('vehicles', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'      => 'required|exists:vehicles,id',
            'priority_class'  => 'required|in:Scheduled,Non-Scheduled,Emergency',
            'odometer'        => 'nullable|integer',
            'completion_date' => 'required|date',
            'start_date'      => 'nullable|date',
            'reference'       => 'nullable|string',
            'vendor_id'       => 'nullable|exists:users,id',
            'labels'          => 'nullable|string',
            'labor_cost'      => 'nullable|numeric',
            'parts_cost'      => 'nullable|numeric',
            'discount'        => 'nullable|numeric',
            'tax'             => 'nullable|numeric',
            'notes'           => 'nullable|string',
            'issues'          => 'array',
            'issues.*'        => 'exists:issues,id',
        ]);

        $validated['total_cost'] = 
            ($validated['labor_cost'] ?? 0) +
            ($validated['parts_cost'] ?? 0) -
            ($validated['discount'] ?? 0) +
            ($validated['tax'] ?? 0);

        $service = ServiceEntry::create($validated);


        // Attach issues if any were selected
        if ($request->has('issues')) {
            $service->issues()->attach($request->issues);

            // Optionally update the status of those issues
            Issues::whereIn('id', $request->issues)
                ->update(['status' => 'Resolved']);
        }

        return redirect()->route('services.index')->with('success', 'Service entry created successfully.');
    }


    public function getIssues($vehicleId)
    {
        $issues = Issues::where('vehicle_id', $vehicleId)
            ->where('status', '!=', 'Closed')
            ->orderBy('reported_at', 'desc')
            ->get(['id', 'summary', 'status']); // keep it light for JSON

        return response()->json($issues);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceEntry $serviceEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceEntry $serviceEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceEntry $serviceEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceEntry $serviceEntry)
    {
        //
    }
}
