<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Issues;
use App\Models\ServiceEntry;
use App\Models\WorkTicket;
use App\Models\Department;
use App\Models\FuelHistory;
use App\Models\WorkOrder;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hour = Carbon::now()->format('H');
        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');

        // === Summary Statistics ===
        $vehiclesCount = Vehicle::count();
        $driversCount = Driver::count();
        $issuesCount = Issues::where('status', 'open')->count();
        $servicesCount = ServiceEntry::count();

        $vehiclesInUse = WorkTicket::whereDate('travel_date', Carbon::today())
            ->where('status', 'approved')->distinct('vehicle_id')->count('vehicle_id');

        $underMaintenance = WorkOrder::where('status', '!=', 'completed')->count();

        $fuelExpensesMonth = FuelHistory::whereMonth('created_at', Carbon::now()->month);

        $avgFuelEfficiency = WorkTicket::whereNotNull('end_mileage')
            ->where('fuel_used', '>', 0)
            ->get()
            ->avg(function ($t) {
                return ($t->end_mileage - $t->start_mileage) / $t->fuel_used;
            });

        // === Utilization Chart ===
        $utilizationData = Vehicle::select('license_plate')
            ->withCount(['workTickets as utilization' => function ($q) {
                $q->whereMonth('created_at', Carbon::now()->month);
            }])
            ->take(5)
            ->get();

        // === Fuel Usage Trend (Line Chart) ===
        $fuelTrend = WorkTicket::selectRaw('MONTH(travel_date) as month, SUM(fuel_used) as total_fuel')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::create()->month($item->month)->format('M');
                return $item;
            });

        // === Departmental Fleet Distribution (Pie Chart) ===
        $departmentFleet = Vehicle::selectRaw('department_id, COUNT(*) as total')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        // === Recent Work Tickets ===
        $recentTickets = WorkTicket::with('vehicle', 'user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('index', compact(
            'greeting',
            'vehiclesCount',
            'driversCount',
            'issuesCount',
            'servicesCount',
            'vehiclesInUse',
            'underMaintenance',
            'fuelExpensesMonth',
            'avgFuelEfficiency',
            'utilizationData',
            'fuelTrend',
            'departmentFleet',
            'recentTickets'
        ));
    }
}
