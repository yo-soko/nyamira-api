<?php $page = Route::is(['department-list', 'department-grid']) ? 'department-grid' : ''; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Departments</h4>
                    <h6>Manage your departments</h6>
                    @if (isset($employee))
    <h1>Welcome, {{ $employee->first_name }} {{ $employee->last_name }}</h1>
    <p>Email: {{ $employee->email }}</p>
    <p>Position: {{ $employee->designation }}</p>
    <p>Department: {{ $employee->department }}</p>
    <!-- Add any other employee-specific details you want to display -->
@endif
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="d-flex me-2 pe-2 border-end">
                        <a href="{{url('department-list')}}" class="btn-list bg-primary me-2"><i data-feather="list" class="feather-user"></i></a>
                        <a href="{{url('department-grid')}}" class="btn-grid bg-primary me-2"><i data-feather="grid" class="feather-user text-white"></i></a>
                    </div>
                </li>
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
         
            <x-modalpopup :hods="$hods" />
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-department"><i class="ti ti-circle-plus me-1"></i>Add Department</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set mb-0">
                        
                        
                    </div>
                    <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
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
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">New Joiners</a>
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
            </div>
        </div>
        @if(Route::is('department-grid'))
        <div class="employee-grid-widget">
            <div class="row"> 
            @foreach($departments as $department)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="d-inline-flex align-items-center">
                                <i class="ti ti-point-filled text-success fs-20"></i>
                                {{ $department->name }}
                            </h5>
                            <div class="dropdown">
                                <a href="#" class="action-icon border-0" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-feather="more-vertical" class="feather-user"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#edit-department" 
                                        data-department-id="{{ $department->id }}" 
                                        data-department-name="{{ $department->name }}"
                                        data-hod-id="{{ $department->hod_id }}" 
                                        data-department-description="{{ $department->description }}">
                                             <i data-feather="edit" class="info-img me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                    <a href="javascript:void(0);" 
                                        class="dropdown-item mb-0" 
                                        data-id="{{ $department->id }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#delete-modal">
                                            <i data-feather="trash-2" class="info-img me-2"></i>Delete
                                    </a>
                                    </li>								
                                </ul>
                            </div>
                        </div>

                        <div class="bg-light rounded p-3 text-center mb-4">
                            <div class="avatar avatar-lg mb-2">
                            <img src="{{ $department->hod && $department->hod->profile_picture ? asset('storage/' . $department->hod->profile_picture) : asset('build/img/users/default.png') }}" alt="HOD Image">
                            </div>
                            <h4>
                                {{ $department->hod?->name ?? 'No HOD Assigned' }}
                            </h4>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0">Total Members: 08</p> {{-- Replace this with actual count if needed --}}
                            <div class="avatar-list-stacked avatar-group-sm">
                                {{-- Optional: You could loop real members here --}}
                                <span class="avatar avatar-rounded">
                                    <img class="border border-white" src="{{URL::asset('build/img/profiles/avatar-15.jpg')}}" alt="img">
                                </span>
                                <span class="avatar avatar-rounded">
                                    <img class="border border-white" src="{{URL::asset('build/img/profiles/avatar-16.jpg')}}" alt="img">
                                </span>
                                <span class="avatar avatar-rounded">
                                    <img class="border border-white" src="{{URL::asset('build/img/profiles/avatar-18.jpg')}}" alt="img">
                                </span>
                                <a class="avatar avatar-rounded text-fixed-white fs-10 fw-medium position-relative" href="javascript:void(0);">
                                    <img src="{{URL::asset('build/img/profiles/avatar-17.jpg')}}" alt="img">
                                    <span class="position-absolute top-50 start-50 translate-middle text-center">+2</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            </div>
        </div>
        @endif
        @if(Route::is('department-list'))
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
                                <th>Department</th>
                                <th>HOD</th>
                                <th>Members</th>
                                <th>Total Members</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $department->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($department->hod)
                                                <a href="#" class="avatar avatar-md">
                                                    <img src="{{ $department->hod->profile_picture ? asset('storage/' . $department->hod->profile_picture) : asset('build/img/users/default.png') }}" class="img-fluid" alt="img">
                                                </a>
                                                <div class="ms-2">
                                                    <p class="text-dark mb-0"><a href="#">{{ $department->hod->name }}</a></p>
                                                </div>
                                            @else
                                                <p>No HOD Assigned</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <!-- Display department members -->
                                        <div class="d-flex align-items-center justify-content-between">
                                            
                                                <div class="avatar avatar-rounded">
                                                    <img class="border border-white" src="" alt="img">
                                                </div>
                                           
                                        </div>						
                                    </td>
                                    <td></td>
                                    <td>
                                        {{ $department->created_at->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Active
                                        </span>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="javascript:void(0);"
                                            data-department-id="{{ $department->id }}" 
                                            data-department-name="{{ $department->name }}"
                                            data-hod-id="{{ $department->hod_id }}" 
                                            data-department-description="{{ $department->description }}"
                                            data-bs-toggle="modal" data-bs-target="#edit-department">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="p-2" href="javascript:void(0);" data-id="{{ $department->id }}" data-bs-toggle="modal" data-bs-target="#delete-modal">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>                                     
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
            const form = document.getElementById('edit-department-form');

            editButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const departmentId = button.getAttribute('data-department-id');
                    const departmentName = button.getAttribute('data-department-name');
                    const hodId = button.getAttribute('data-hod-id');
                    const departmentDescription = button.getAttribute('data-department-description');
                    const status = button.getAttribute('data-department-status');


                    // Set form action dynamically
                    form.action = `/department/${departmentId}`;

                    // Set the values in the modal
                    document.getElementById('department-id').value = departmentId;
                    document.getElementById('department-name').value = departmentName;
                    document.getElementById('hod-id').value = hodId;
                    document.getElementById('edit-status').checked = status === "1" || status === 1;
                    $('#summernote2').summernote('code', departmentDescription);
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


    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
@endsection
