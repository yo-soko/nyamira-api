@role('Admin')
<?php $page = 'leaves-admin'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Leaves</h4>
                    <h6>Manage your Leaves</h6>
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
                    <div class="me-2 date-select-small">
                        <div class="input-addon-left position-relative">
                            <input type="text" class="form-control datetimepicker" placeholder="Select Date">
                            <span class="cus-icon"><i data-feather="calendar" class="feather-clock"></i></span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Select Status
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Approved</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Rejected</a>
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
                                <th>User</th>
                                <th>Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Days</th>
                                <th>Applied  On</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>	
                            @forelse($leaves as $leave)
                                <tr>
                                    <td>
                                        @if($leave->status === 'Pending')
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                       <div class="d-flex align-items-center">
                                            <a href="#" class="avatar avatar-md me-2">
                                                <img src="{{ $leave->user->profile_picture ? asset('storage/' . $leave->user->profile_picture) : asset('build/img/users/default.png') }}" alt="user">
                                            </a>
                                            <div>
                                                <h6><a href="#">{{ $leave->user->name }}</a></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $leave->leaveType->type ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                    <td>{{ $leave->days ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}</td>
                                    <td>{{ $leave->leave_mode ?? 'Full Day' }}</td>
                                    <td>
                                        @if($leave->status === 'Approved')
                                            <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                                <i class="ti ti-point-filled me-1"></i>Approved
                                            </span>
                                        @elseif($leave->status === 'Rejected')
                                            <span class="badge badge-danger d-inline-flex align-items-center badge-xs">
                                                <i class="ti ti-point-filled me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge badge-teal d-inline-flex align-items-center badge-xs">
                                                <i class="ti ti-point-filled me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        @if($leave->status === 'Pending')
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 bg-success" href="{{ route('leave.approve', $leave->id) }}">
                                                    <i class="feather-check"></i>
                                                </a>
                                                <a class="p-2 bg-danger" href="{{ route('leave.reject', $leave->id) }}">
                                                    <i class="feather-x"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted"></span>
                                        @endif
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
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

@endsection
@endrole