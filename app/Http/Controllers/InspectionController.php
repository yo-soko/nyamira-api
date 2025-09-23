<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionItem;
use App\Models\InspectionMedia;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $inspections = Inspection::with(['vehicle','inspector'])->latest()->paginate(10);
        $vehicles = Vehicle::all();
        $checklist = [
            'Engine','Transmission','Clutch','Steering Mechanism','Horn',
            'Windshield & Wipers','Rear Vision Mirrors','Lighting Devices','Body Works',
            'Parking Brake','Service Brakes','Tires','Wheels & Rims','Emergency Equipment'
        ];
        return view('inspections.index', compact('inspections','vehicles','checklist'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $checklist = [
            'Engine','Transmission','Clutch','Steering Mechanism','Horn',
            'Windshield & Wipers','Rear Vision Mirrors','Lighting Devices','Oil Life Left',
            'Parking Brake','Service Brakes','Tires','Wheels & Rims','Emergency Equipment'
        ];
        return view('inspections.create', compact('vehicles','checklist'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'odometer_reading' => 'nullable|integer',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.status' => 'required|in:Pass,Fail,N/A',
            'items.*.remark' => 'nullable|string',
            'items.*.attachment' => 'nullable|file|max:2048',
        ]);
        $inspection = Inspection::create([
            'vehicle_id' => $request->vehicle_id,
            'inspector_id' => Auth::id(),
            'inspection_date' => now(),
            'odometer_reading' => $request->odometer_reading,
            'notes' => $request->notes,
            'status' => collect($request->items)->contains('status','Fail') ? 'Fail' : 'Pass',
        ]);

        foreach ($request->items as $name => $data) {
            $item = new InspectionItem([
                'item_name' => $name,
                'status' => $data['status'],
                'remark' => $data['remark'] ?? null,
            ]);

            if (isset($data['attachment'])) {
                $item->attachment = $data['attachment']->store('inspection_items','public');
            }

            $inspection->items()->save($item);
        }

        return redirect()->route('inspections.index')->with('success','Inspection saved successfully.');
    }

    public function schedules()
    {
        // Get the latest inspection per vehicle
        $vehicles = \DB::table('inspections as i')
            ->select('i.vehicle_id', \DB::raw('MAX(i.inspection_date) as last_inspection_date'))
            ->groupBy('i.vehicle_id');

        // Join with vehicles table to fetch details
        $schedules = \DB::table('vehicles as v')
            ->leftJoinSub($vehicles, 'latest', function($join) {
                $join->on('v.id', '=', 'latest.vehicle_id');
            })
            ->select(
                'v.id',
                'v.name',
                'v.license_plate',
                \DB::raw("DATE_ADD(latest.last_inspection_date, INTERVAL 3 MONTH) as next_inspection_date")
            )
            ->orderBy('next_inspection_date', 'asc')
            ->get();

        return view('schedules', compact('schedules'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspection $inspection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspection $inspection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inspection $inspection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inspection $inspection)
    {
        //
    }
}
