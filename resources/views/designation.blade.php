<?php $page = 'designation'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Designation</h4>
                    <h6>Manage your designation</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            @role('Admin')
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-department"><i class="ti ti-circle-plus me-1"></i>Add Designation</a>
            </div>
            @endrole
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Department
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Sales</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Inventory</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Finance</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Select Status
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Active</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Inactive</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Sort By : Last 7 Days
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recently Added</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Ascending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="no-sort">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Members</th>
                                <th>Created On</th>
                                <th>Status</th>
                                @role('Admin')
                                <th class="no-sort"></th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($designations as $designation)
                            <tr>
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td>
                                    <span class="text-gray-900">{{ $designation->designation }}</span>
                                </td>
                                <td>{{ $designation->department->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-between">
                                        @php
                                            $designationStaff = $designation->employees->filter(fn($e) => $e->user && $e->user->role === 'Employee');
                                        @endphp

                                        <p class="mb-0">Total Members: {{ $designationStaff->count() }}</p>

                                        <div class="avatar-list-stacked avatar-group-sm">
                                            @foreach($designationStaff->take(3) as $employee)
                                                <span class="avatar avatar-rounded" title="{{ $employee->user->name }}">
                                                    <img class="border border-white" 
                                                        src="{{ $employee->user->profile_picture ? asset('storage/' . $employee->user->profile_picture) : asset('build/img/users/default.png') }}" 
                                                        alt="{{ $employee->user->name }}">
                                                </span>
                                            @endforeach

                                            @if($designationStaff->count() > 3)
                                                <a class="avatar avatar-rounded text-fixed-white fs-10 fw-medium position-relative" href="javascript:void(0);" title="More members">
                                                    <img src="{{ asset('build/img/users/default.png') }}" alt="+">
                                                    <span class="position-absolute top-50 start-50 translate-middle text-center">
                                                        +{{ $designationStaff->count() - 3 }}
                                                    </span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                               
                                <td>{{ \Carbon\Carbon::parse($designation->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($designation->status)
                                        <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                @role('Admin')
                                <td class="action-table-data">
                                    <div class="edit-delete-action">
                                        <a href="javascript:void(0);" class="edit-btn" 
                                            data-id="{{ $designation->id }}"
                                            data-designation="{{ $designation->designation }}"
                                            data-department="{{ $designation->department->id }}"
                                            data-status="{{ $designation->status }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit-department">
                                                <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" 
                                            class="delete-btn" 
                                            data-id="{{ $designation->id }}"  
                                            data-bs-toggle="modal" 
                                            data-bs-target="#delete-modal">
                                                <i data-feather="trash-2" class="feather-trash"></i>
                                        </a>
                                    </div>
                                </td>
                                @endrole
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const designation = this.dataset.designation;
                        const department = this.dataset.department;
                        const status = this.dataset.status;

                        document.getElementById('edit-id').value = id;
                        document.getElementById('edit-designation').value = designation;
                        document.getElementById('edit-depart').value =department;
                        document.getElementById('edit-status').checked = status == 1;

                        // Set form action dynamically
                        document.getElementById('editForm').action = `/designation/${id}`;
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        document.getElementById('delete-id').value = id;
                    });
                });
            });
        </script>

        <!-- /product list -->
        
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>   

@endsection
