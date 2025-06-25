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

    <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill bg-secondary-gradient">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                 
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ $summary['total_subjects'] ?? '-'}}</h2>
                            <p class="fs-13">Total Subjects</p>
                        </div>
                        <div class="company-bar1"></div>
                    </div>
                </div>
            </div>
        </div>    
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill bg-secondary-gradient">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                 
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ $summary['term_name'] ?? '-' }}</h2>
                            <p class="fs-13">Term</p>
                        </div>
                        <div class="company-bar1"></div>
                    </div>
                </div>
            </div>
        </div> 
            <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill bg-secondary-gradient">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                 
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1"> {{ $student->class->level->level_name ?? '' }} - {{ $student->class->stream->name ?? '' }}</h2>
                            <p class="fs-13">Class</p>
                        </div>
                        <div class="company-bar1"></div>
                    </div>
                </div>
            </div>
        </div> 
                 <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill bg-secondary-gradient">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                 
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ $summary['grade'] ?? '-'}}</h2>
                            <p class="fs-13">Grade</p>
                        </div>
                        <div class="company-bar1"></div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Comment</th>
                            <th>Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($marks as $mark)
                            <tr>
                                <td>{{ $mark->subject->subject_name ?? '-' }}</td>
                                <td>{{ $mark->grade ?? '-'}}</td>
                                <td>{{ $mark->comments ?? '-' }}</td>
                                <td>{{ $mark->absent ? 'Yes' : 'No' }}</td>
                            </tr>
                        @empty
                          
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>


@endsection