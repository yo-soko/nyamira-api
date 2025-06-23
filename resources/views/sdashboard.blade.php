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
                    {{ $greeting }} <span class="text-primary fw-bold"> {{ auth()->user()->name }}</span>
                 </h2>
                <p>Its nice  to see you <span class="text-primary fw-bold">Today</span></p>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="input-icon-start position-relative">
                        <span class="input-icon-addon fs-16 text-gray-9">
                            <i class="ti ti-calendar"></i>
                        </span>
                        <input type="text" class="form-control date-range bookingrange" placeholder="Search Product">
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse" class=""><i data-feather="chevron-up" class="feather-16"></i></a>
                </li>
            </ul>
        </div>
        <!-- Welcome Wrap -->
    <div class="welcome-wrap mb-4">
        <div class=" d-flex align-items-center justify-content-between flex-wrap">
            <div class="mb-3">
                <h2 class="mb-1 text-white">Welcome Back,  {{ auth()->user()->name }}</h2>
            </div>
            <div class="d-flex align-items-center flex-wrap mb-1">
                <a href="{{url('profile')}}" class="btn btn-dark btn-md me-2 mb-2">Profile</a>
                @hasanyrole('admin|developer|manager|director|supervisor')
                        <a href="{{ url('auto-clockout') }}" class="btn btn-dark btn-md me-2 mb-2"
                            onclick="return confirm('Are you sure you want to auto-clock out employees who forgot?');">
                            <i class="ti ti-circle-plus me-1"></i>Clock out forgotten
                        </a>
                @endhasanyrole
                <a href="{{url('general-settings')}}" class="btn btn-light btn-md mb-2">Settings</a>
            </div>
        </div>
        <div class="welcome-bg">
            <img src="{{URL::asset('build/img/bg/welcome-bg-02.svg')}}" alt="img" class="welcome-bg-01">
            <img src="{{URL::asset('build/img/bg/welcome-bg-01.svg')}}" alt="img" class="welcome-bg-03">
        </div>
    </div>	
    <!-- /Welcome Wrap -->

    <div class="row">

      
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
  <x-summary-card title="Total Subjects" :value="$summary['total_subjects']" />
  <x-summary-card title="Total Marks" :value="$summary['total_marks']" />
  <x-summary-card title="Average" :value="$summary['average']" />
  <x-summary-card title="Grade" :value="$summary['grade']" />
</div>
@php
    $subjects = $marks->pluck('subject.name')->toArray();
    $marksData = $marks->pluck('marks')->toArray();
@endphp
<div class="mt-8">
    <h2 class="text-lg font-bold mb-2">Performance Chart ({{ $summary['exam_name'] }})</h2>
    <canvas id="performanceChart" height="100"></canvas>
</div>

<table class="table-auto w-full mt-6">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Marks</th>
            <th>Grade</th>
            <th>Comment</th>
            <th>Absent</th>
        </tr>
    </thead>
    <tbody>
        @forelse($marks as $mark)
            <tr>
                <td>{{ $mark->subject->name }}</td>
                <td>{{ $mark->marks }}</td>
                <td>{{ $mark->grade }}</td>
                <td>{{ $mark->comments }}</td>
                <td>{{ $mark->absent ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No marks found.</td></tr>
        @endforelse
    </tbody>
</table>


    <div class="row">

        <!-- Companies -->
        <div class="col-xxl-3 col-lg-6 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2">Production</h5>								
                    <div class="dropdown mb-2">
                        <a href="javascript:void(0);" class="btn btn-white border btn-sm d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="ti ti-calendar me-1"></i>This Week
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">This Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">This Week</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Today</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="company-chart"></div>
                    <p class="f-13 d-inline-flex align-items-center"><span class="badge badge-success me-1">+6%</span> Increased production</p>
                </div>
            </div>
        </div>
        <!-- /Companies -->
        
        <!-- Revenue -->
        <div class="col-lg-6 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2">Overtime</h5>								
                    <div class="dropdown mb-2">
                        <a href="javascript:void(0);" class="btn btn-white border btn-sm d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="ti ti-calendar me-1"></i>2025
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">2024</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">2025</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">2023</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="mb-1">
                            <h5 class="mb-1">40:54:12 hrs</h5>
                            <p><span class="text-success fw-bold">+40%</span> increased from last month</p>
                        </div>
                        <p class="fs-13 text-gray-9 d-flex align-items-center mb-1"><i class="ti ti-circle-filled me-1 fs-6 text-primary"></i>overtime</p>
                    </div>
                    <div id="revenue-income"></div>
                </div>
            </div>
        </div>
        <!-- /Revenue -->

        <!-- Top Plans -->
        <div class="col-xxl-3 col-xl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2">Top Projects</h5>							
                    <div class="dropdown mb-2">
                        <a href="javascript:void(0);" class="btn btn-white border btn-sm d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="ti ti-calendar me-1"></i>This Month
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">This Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">This Week</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Today</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="plan-overview"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-primary me-1"></i>HR system</p>
                        <p class="f-13 fw-medium text-gray-9">90%</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-warning me-1"></i>School system</p>
                        <p class="f-13 fw-medium text-gray-9">95%</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-0">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-info me-1"></i>Hospital system</p>
                        <p class="f-13 fw-medium text-gray-9">80%</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Top Plans -->

    </div>

    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('performanceChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($marks->pluck('subject.name')) !!},
                datasets: [{
                    label: 'Marks',
                    data: {!! json_encode($marks->pluck('marks')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    });
</script>

@endsection