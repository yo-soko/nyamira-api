<?php $page = 'leaves-employee'; ?>
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
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-leave">Apply Leave</a>
            </div>
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
                                <th>Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Days/Hours</th>
                                <th>Reason</th>
                                <th>Applied On</th>
                                <th>Status</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaves as $leave)
                            <tr>
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td>{{ $leave->leaveType->type ?? 'N/A' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}						
                                </td>
                                <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                <td>
                                   {{ $leave->days == 0.5 ? 'Half Day' : number_format($leave->days, 1) . ' Day' }}
                                </td>
                                 <td>{{ $leave->reason ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if($leave->status == 'Pending')
                                        <span class="badge badge-teal d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Pending
                                        </span>
                                    @elseif($leave->status == 'Approved')
                                        <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="badge badge-danger d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="action-table-data justify-content-end">
                                    @if($leave->status === 'Pending')
                                    <div class="edit-delete-action">
                                       <a class="me-2 p-2 edit-btn"
                                        href="#"
                                        data-id="{{ $leave->id }}"
                                        data-leave="{{ $leave->leave_type_id }}"
                                        data-from_date="{{ $leave->from_date }}"
                                        data-to_date="{{ $leave->to_date }}"
                                        data-days_count="{{ $leave->days }}"
                                        data-description="{{ htmlentities($leave->reason) }}"
                                        data-leave_mode="{{ $leave->leave_mode }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit-leave">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>

                                        <a href="javascript:void(0);" 
                                            class="delete-btn" 
                                            data-id="{{ $leave->id }}"
                                            data-bs-target="#delete-modal" data-bs-toggle="modal">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                    </div>
                                </td>
                                @else
                                    <span class="text-muted">No Actions</span>
                                @endif
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
<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            function formatToYMD(dateStr) {
                const parts = dateStr.split('-'); //  d-m-Y format
                return `${parts[2]}-${parts[1]}-${parts[0]}`; // Returns Y-m-d
            }
            const id = this.dataset.id;
            const leave_type_id = this.dataset.leave;
            const from_date = formatToYMD(this.dataset.from_date);
            const to_date = formatToYMD(this.dataset.to_date);
            const days_count = this.dataset.days_count;
            const description = this.dataset.description;
            const leave_mode = this.dataset.leave_mode;

            document.getElementById('edit-id').value = id;
            document.getElementById('leave_type_id').value = leave_type_id;
            document.getElementById('from_date').value = from_date;
            document.getElementById('to_date').value = to_date;
            document.getElementById('days').value = days_count;
            document.getElementById('description').value = description;
            document.getElementById('leave_mode').value = leave_mode;

            document.getElementById('editForm').action = `/leaves/${id}`;
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
@endsection
