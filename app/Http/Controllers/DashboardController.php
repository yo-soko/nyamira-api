<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Issues;
use App\Models\ServiceEntry;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hour = Carbon::now()->format('H');

        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 18) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }
        $today = Carbon::today();
        $vehiclesCount = Vehicle::count();
        $driversCount = Driver::count();
        $issuesCount = Issues::where('status', 'open')->count();
        $servicesCount = ServiceEntry::whereDate('created_at', '>=', Carbon::today())->count();
       
        $utilizationData = Vehicle::select('license_plate')
            ->withCount(['workTickets as utilization' => function ($q) {
                $q->whereMonth('created_at', Carbon::now()->month);
            }])
            ->take(5)
            ->get();
        
        return view('index', compact(
            'greeting',
            'vehiclesCount',
            'driversCount',
            'issuesCount',
            'servicesCount',
            'utilizationData'
        ));
    }
}
