<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Issues;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workOrders = WorkOrder::with(['vehicle', 'issuedBy', 'assignedTo', 'vendor'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $users = User::all();
        $issues = Issues::where('status', 'Open')->get();
        return view('work_orders.index', compact('workOrders','vehicles', 'users', 'issues'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $users = User::all();
        $issues = Issue::where('status', 'Open')->get();

        return view('work_orders.create', compact('vehicles', 'users', 'issues'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'status' => 'required|in:Pending,In Progress,Completed,Voided',
            'priority_class' => 'required|in:Scheduled,Non-Scheduled,Emergency',
            'issue_date' => 'required|date',
            'issued_by' => 'required|exists:users,id',
            'scheduled_start_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'expected_completion_date' => 'nullable|date',
            'actual_completion_date' => 'nullable|date',
            'start_odometer' => 'nullable|integer',
            'completion_odometer' => 'nullable|integer',
            'assigned_to' => 'nullable|exists:users,id',
            'labels' => 'nullable|string',
            'vendor_id' => 'nullable|exists:users,id',
            'invoice_number' => 'nullable|string',
            'po_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'labor_cost' => 'nullable|numeric|min:0',
            'parts_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'issues' => 'array',
            'issues.*' => 'exists:issues,id',
        ]);

        // Default numeric values to 0 if not provided
        $validated['labor_cost'] = $validated['labor_cost'] ?? 0;
        $validated['parts_cost'] = $validated['parts_cost'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['tax'] = $validated['tax'] ?? 0;

        // Calculate total cost
        $validated['total_cost'] =
            $validated['labor_cost'] +
            $validated['parts_cost'] -
            $validated['discount'] +
            $validated['tax'];

        // Create Work Order
        $workOrder = WorkOrder::create($validated);

        // Attach issues (ensure pivot table is correct)
        if ($request->has('issues')) {
            // if using issue_work_order pivot:
            $workOrder->issues()->attach($request->issues);

            // if your schema only supports issue_service_entry,
            // then we should instead attach via ServiceEntry
            // and link those to work orders.
        }

        return redirect()
            ->route('work_orders.index')
            ->with('success', 'Work order created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
    }
}
