<?php $page = 'employees-list'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Employees</h4>
                        <h6>Manage your employees</h6>
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
                <div class="page-btn">
                    <a href="{{url('add-employee')}}" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>Add Employee</a>
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
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
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
                                    <th>EMP No.</th>
                                    <th>Employee</th>
                                    <th>Designation</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Shift</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <a href="{{ url('employee-details/' . $employee->id) }}">{{ str_pad($employee->emp_code, 3, '0', STR_PAD_LEFT) }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ url('employee-details/' . $employee->id) }}" class="avatar avatar-md">
                                                    <img src="{{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : asset('build/img/users/user-32.jpg') }}" class="img-fluid" alt="Profile">
                                                </a>
                                                <div class="ms-2">
                                                    <p class="text-dark mb-0">
                                                        <a href="{{ url('employee-details/' . $employee->id) }}">
                                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $employee->designation }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->contact_number }}</td>
                                        <td>{{ $employee->shift }}</td>
                                        <td>
                                            <span class="badge {{ $employee->status === 1 ? 'badge-success' : 'badge-danger' }} d-inline-flex align-items-center badge-xs">
                                                <i class="ti ti-point-filled me-1"></i>{{ $employee->status === 1 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="edit-delete-action d-flex align-items-center">
                                                <a class="me-2 d-flex align-items-center border rounded p-2" href="{{ url('employee-details/' . $employee->id) }}">
                                                    <i data-feather="eye" class="feather-eye"></i>
                                                </a>
                                                <a class="me-2 p-2 d-flex align-items-center border rounded" href="{{ url('edit-employee/' . $employee->id) }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" 
                                                    class="p-2 d-flex align-items-center border rounded delete-btn" 
                                                    data-id="{{ $employee->id }}" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#delete-modal">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                   
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->

        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteForm = document.getElementById('delete-form');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const employeeId = this.getAttribute('data-id');
                    deleteForm.setAttribute('action', `/employees/${employeeId}`);
                });
            });
        });
    </script>

@endsection
