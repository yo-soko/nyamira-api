<?php $page = 'employee-details'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div>
                    <a href="{{url('employees-list')}}" class="d-inline-flex align-items-center"><i class="ti ti-chevron-left me-2"></i>Back to List</a>
                </div>
                <ul class="table-top-head">
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-xl-4 theiaStickySidebar">
                    <div class="card rounded-0 border-0">
                        <div class="card-header rounded-0 bg-primary d-flex align-items-center">
                            <span class="avatar avatar-xl avatar-rounded flex-shrink-0 border border-white border-3 me-3">
                                <img src="{{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : asset('build/img/users/user-32.jpg') }}" alt="Img"> <!-- Assuming the avatar is stored in storage -->
                            </span>
                            <div class="me-3">
                                <h6 class="text-white mb-1">{{ $employee->name }}</h6> 
                                <span class="badge bg-purple-transparent text-purple">{{ $employee->designation }}</span>
                            </div>
                            <div>
                                <a href="#" class="btn btn-white">Edit Profile</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="d-inline-flex align-items-center">
                                    <i class="ti ti-id me-2"></i>
                                    Employee ID
                                </span>
                                <p class="text-dark">{{ $employee->emp_code }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="d-inline-flex align-items-center">
                                    <i class="ti ti-star me-2"></i>
                                    Team
                                </span>
                                <p class="text-dark">{{ $employee->department }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-inline-flex align-items-center">
                                    <i class="ti ti-calendar-check me-2"></i>
                                    Date Of Join
                                </span>
                                <p class="text-dark">{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</p> <!-- Format date -->
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-inline-flex align-items-center">
                                    <i class="ti ti-circle-check me-2"></i> 
                                    Status
                                </span>
                                <p class="text-dark">
                                    <span class="badge {{ $employee->status === 1 ? 'badge-success' : 'badge-danger' }} d-inline-flex align-items-center badge-xs">
                                        <i class="ti ti-point-filled me-1"></i>{{ $employee->status === 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card rounded-0 border-0">
                        <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                            <h6>Basic information</h6>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Phone</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->contact_number }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Email</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->email }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Gender</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->gender }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Birthday</p>
                                        <span class="text-gray-900 fs-13">{{ \Carbon\Carbon::parse($employee->dob)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Address</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->address }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Nationality</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->nationality }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Blood Group</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->blood_group }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Shift</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->shift }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-0 border-0">
                        <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                            <h6>About Employee</h6>
                        </div>
                        <div class="card-body pb-0">
                            <p>{!! $employee->about !!}</p>
                        </div>
                    </div>
                    <div class="card rounded-0 border-0">
                        <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                            <h6>Bank Information</h6>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Bank Name</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->bank_name }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Bank Account No</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->account_number }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Branch</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->branch }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-0 border-0">
                        <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                            <h6>Emergency Contact Number 1</h6>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Name</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->emergency_contact1}}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Relation</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->emergency_relation1 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Phone Number</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->emergency_contact1 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card rounded-0 border-0">
                        <div class="card-header border-0 rounded-0 bg-light d-flex align-items-center">
                            <h6>Emergency Contact Number 2</h6>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Name</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->emergency_contact2}}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Relation</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->emergency_relation2 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <p class="fs-13 mb-2">Phone Number</p>
                                        <span class="text-gray-900 fs-13">{{ $employee->emergency_contact2 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>

@endsection