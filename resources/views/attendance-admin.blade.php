<?php $page = 'employees-grid'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast') 
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Attendancies Corner</h4>
                    <h6>Mark attendancies</h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-purple border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white">Total Employee</p>
                       
                            <h4 class="text-white">{{ $totalEmployees }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-lg bg-purple-900"><i class="ti ti-users-group"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-teal border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white">Active</p>
                            <h4 class="text-white">{{ $activeEmployees }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-lg bg-teal-900"><i class="ti ti-user-star"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-secondary border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white">Inactive</p>
                            <h4 class="text-white">{{ $inactiveEmployees }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-lg bg-secondary-900"><i class="ti ti-user-exclamation"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white">New Joiners</p>
                            <h4 class="text-white">{{ $newJoiners }}</h4>
                        </div>
                        <div>
                            <span class="avatar avatar-lg bg-info-900"><i class="ti ti-user-check"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set mb-0">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                            <input type="search" class="form-control" placeholder="Search">
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- /product list -->
        
        <div class="employee-grid-widget">
            <div class="row">
                @foreach($employees as $employee) 
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="form-check form-check-md">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                                <div>
                                   <form action="{{ route('attendance-employee') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                        <button type="submit" class="avatar avatar-xl avatar-rounded border p-1 rounded-circle" style="border:none; background:none; padding:0;">
                                            <img src="{{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : asset('build/img/users/profile.jpg') }}" class="img-fluid h-auto w-auto" alt="img" />
                                        </button>
                                    </form>

                                </div>
                                <div class="dropdown">
                                    <a href="#" class="action-icon border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical" class="feather-user"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form action="{{ route('attendance-employee') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                <button type="submit" class="dropdown-item" style="border: none; background: none; padding: 0; width: 100%; text-align: left;">
                                                    <i class="ti ti-circle-check me-2"></i> Mark attendance
                                                </button>
                                            </form>
                                        </li>
                                       								
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-primary mb-2">EMP ID : {{ $employee->emp_code }}</p>
                            </div>
                            <div class="text-center mb-3">
                                <h6 class="mb-1">
                                    <a href="{{ url('attendance-employee/'.$employee->id) }}">{{ $employee->first_name .' '. $employee->last_name }}</a>
                                </h6>
                                <span class="badge bg-secondary-gradient text-gray-9 fs-10 fw-medium">{{ $employee->designation->designation ?? '-' }}</span>
                                <br class="mb-2">
                                <span class="badge {{ $employee->status === 1 ? 'badge-success' : 'badge-danger' }} d-inline-flex align-items-center badge-xs">
                                    <i class="ti ti-point-filled me-1"></i>{{ $employee->status === 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between bg-light rounded p-3">
                                <div class="text-start">
                                    <h6 class="mb-1">Joined</h6>
                                    <p>{{ \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') }}</p>
                                </div>
                                <div class="text-start">
                                    <h6 class="mb-1">Department</h6>
                                    <p>{{ $employee->department->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

@endsection
