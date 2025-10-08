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
    
    <!-- Stats Cards -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Total Vehicles</h5>
            <h2 class="text-primary">{{ $vehiclesCount ?? 0 }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Active Drivers</h5>
            <h2 class="text-success">{{ $driversCount ?? 0 }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Open Issues</h5>
            <h2 class="text-danger">{{ $issuesCount ?? 0 }}</h2>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card text-center shadow-sm border-0 p-3">
            <h5>Upcoming Services</h5>
            <h2 class="text-warning">{{ $servicesCount ?? 0 }}</h2>
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
    const ctx = document.getElementById('utilizationChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($utilizationData->pluck('name')),
            datasets: [{
                label: 'Work Tickets This Month',
                data: @json($utilizationData->pluck('utilization')),
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

</script>
@endpush

@endsection
@endcan