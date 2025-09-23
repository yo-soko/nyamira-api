<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\Inspection;
use App\Models\Issues;
use App\Models\User;
use App\Models\FuelHistory;
use App\Models\ServiceEntry;
use App\Models\VehicleAssignment;

class ReportController extends Controller
{
    public function index()
    {
        $categories = [
            'Vehicles' => [
                ['name' => 'Vehicle List', 'route' => 'reports.vehicles'],
                ['name' => 'Vehicle Assignments', 'route' => 'reports.vehicle_assignments'],
            ],
            'Inspections' => [
                ['name' => 'Inspection Summary', 'route' => 'reports.inspections'],
                ['name' => 'Inspection Failures', 'route' => 'reports.inspections_failures'],
            ],
            'Issues' => [
                ['name' => 'Issue Summary', 'route' => 'reports.issues'],
            ],
            'Service' => [
                ['name' => 'Service Entries', 'route' => 'reports.service'],
            ],
            'Work Orders' => [
                ['name' => 'Work Orders List', 'route' => 'reports.work_orders'],
                ['name' => 'Work Orders by Vehicle', 'route' => 'reports.work_orders_vehicle'],
            ],
            'Fuel' => [
                ['name' => 'Fuel History', 'route' => 'reports.fuel'],
            ],
        ];

        return view('reports.index', compact('categories'));
    }
     public function vehicles()
    {
        $vehicles = Vehicle::with('operator')->get();
        return view('reports.vehicles', compact('vehicles'));
    }
    public function drivers()
    {
        $drivers = User::whereIn('role', ['driver'])->get();
        return view('reports.drivers', compact('drivers'));
    }
    public function vehicleAssignments()
    {
        $assignments = VehicleAssignment::with(['vehicle', 'operator'])->get();
        return view('reports.vehicle_assignments', compact('assignments'));
    }

    public function inspections()
    {
        $inspections = Inspection::with(['vehicle', 'inspector'])->latest()->get();
        return view('reports.inspections', compact('inspections'));
    }

    public function inspectionsFailures()
    {
        $inspections = Inspection::with('items')
            ->whereHas('items', function($q) {
                $q->where('status', 'Fail');
            })->get();

        return view('reports.inspections_failures', compact('inspections'));
    }

    public function issues()
    {
        $issues = Issues::with('vehicle')->get();
        return view('reports.issues', compact('issues'));
    }

    public function service()
    {
        $services = ServiceEntry::with('vehicle')->get();
        return view('reports.service', compact('services'));
    }

    public function workOrders()
    {
        $orders = WorkOrder::with('vehicle')->get();
        return view('reports.work_orders', compact('orders'));
    }

    public function workOrdersByVehicle()
    {
        $orders = WorkOrder::with('vehicle')->orderBy('vehicle_id')->get()->groupBy('vehicle_id');
        return view('reports.work_orders_vehicle', compact('orders'));
    }

    public function fuel()
    {
        $fuels = FuelHistory::with('vehicle')->get();
        return view('reports.fuel', compact('fuels'));
    }
}
