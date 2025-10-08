@can('view dashboard')
<?php $page = 'dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast') 
@php
    $hour = date('H');
    if ($hour < 12) {
        $greeting = 'Good Morning';
    } elseif ($hour < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }
@endphp    
<div class="page-wrapper">
    <div class="content">
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>
             
                <h2 class="mb-1">
                <img src="{{URL::asset('build/img/icons/hand01.svg')}}" class="hand-img" alt="img">
                    {{ $greeting }} <span class="text-primary fw-bold"> Directorate of Artificial Intelligence, Automation and Research</span>
                 </h2>
                <p>Its nice  to see you <span class="text-primary fw-bold">Today</span></p>
            </div>
           
        </div>
        <!-- Welcome Wrap -->
        <div class="welcome-wrap mb-4">
            <div class=" d-flex align-items-center justify-content-between flex-wrap">
                <div class="mb-3">
                    <h2 class="mb-1 text-white">Welcome Back,  {{ auth()->user()->name }}</h2>
                </div>
            
            </div>
            <div class="welcome-bg">
                <img src="{{URL::asset('build/img/bg/welcome-bg-02.svg')}}" alt="img" class="welcome-bg-01">
                <img src="{{URL::asset('build/img/bg/welcome-bg-01.svg')}}" alt="img" class="welcome-bg-03">
            </div>
        </div>	
        <!-- /Welcome Wrap -->

        <div class="row">

    <!-- Quick Setup / Getting Started -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Getting Started</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="ti ti-circle-check text-success me-2"></i> Add your first <a href="#">Vehicle</a></li>
                    <li class="mb-2"><i class="ti ti-circle-check text-success me-2"></i> Add your first <a href="#">Driver</a></li>
                    <li class="mb-2"><i class="ti ti-circle-check text-success me-2"></i> Record a <a href="#">Service</a></li>
                    <li class="mb-2"><i class="ti ti-circle-check text-success me-2"></i> Upload your <a href="#">Documents</a></li>
                </ul>
                <a href="#" class="btn btn-primary mt-2">Take a Tour</a>
            </div>
        </div>
    </div>

    <!-- Utilization Summary Example -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Utilization Summary</h4>
                <canvas id="utilizationChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Summary Cards -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Total Vehicles</h5>
            <h2 class="text-primary">{{ $vehiclesCount }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Active Drivers</h5>
            <h2 class="text-success">{{ $driversCount }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Vehicles In Use Today</h5>
            <h2 class="text-info">{{ $vehiclesInUse }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Under Maintenance</h5>
            <h2 class="text-danger">{{ $underMaintenance }}</h2>
        </div>
    </div>
</div>

<div class="row">
    <!-- Utilization Chart -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Vehicle Utilization (This Month)</h4>
                <canvas id="utilizationChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Fuel Trend Chart -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Fuel Usage Trend</h4>
                <canvas id="fuelTrendChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Department Fleet Distribution -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Fleet by Department</h4>
                <canvas id="departmentFleetChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Work Tickets -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Recent Work Tickets</h4>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTickets as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->vehicle->license_plate ?? '-' }}</td>
                                <td>{{ $t->user->name ?? '-' }}</td>
                                <td>{{ $t->travel_date?->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $t->status == 'approved' ? 'success' : ($t->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


    </div>
            
      
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy;  All Right Reserved</p>
        <!-- <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary"></a></p> -->
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Utilization Bar Chart
    new Chart(document.getElementById('utilizationChart'), {
        type: 'bar',
        data: {
            labels: @json($utilizationData->pluck('license_plate')),
            datasets: [{
                label: 'Trips This Month',
                data: @json($utilizationData->pluck('utilization')),
                backgroundColor: '#4e73df'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Fuel Trend Line Chart
    new Chart(document.getElementById('fuelTrendChart'), {
        type: 'line',
        data: {
            labels: @json($fuelTrend->pluck('month_name')),
            datasets: [{
                label: 'Fuel Used (Litres)',
                data: @json($fuelTrend->pluck('total_fuel')),
                borderColor: '#f6c23e',
                fill: false,
                tension: 0.3
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Department Fleet Pie Chart
    new Chart(document.getElementById('departmentFleetChart'), {
        type: 'pie',
        data: {
            labels: @json($departmentFleet->pluck('department.name')),
            datasets: [{
                data: @json($departmentFleet->pluck('total')),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
            }]
        },
        options: { responsive: true }
    });
</script>
@endpush


@endsection
@endcan