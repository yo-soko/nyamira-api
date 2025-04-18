<?php $page = 'holidays'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')   
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Holiday</h4>
                    <h6>Manage your holidays</h6>
                </div>						
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>						
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-holiday"><i class="ti ti-circle-plus me-1"></i>Add Holiday</a>
            </div>
        </div>
        <!-- product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    
                    
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                             Status
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
                                <th>Date</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($holidays as $holiday)
                            <tr>
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox">
                                        <span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td class="text-gray-9">
                                    {{ $holiday->holiday_name }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($holiday->from_date)->format('d M Y') }}
                                    @if($holiday->from_date != $holiday->to_date)
                                        - {{ \Carbon\Carbon::parse($holiday->to_date)->format('d M Y') }}
                                    @endif
                                </td>
                                <td>{!! $holiday->description !!}</td>
                                <td>
                                    @if($holiday->status)
                                        <span class="badge badge-success d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge badge-danger d-inline-flex align-items-center badge-xs">
                                            <i class="ti ti-point-filled me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="action-table-data">
                                    <div class="edit-delete-action">
                                    <a class="me-2 p-2 edit-btn" href="#" 
                                            data-id="{{ $holiday->id }}"
                                            data-holiday_name="{{ $holiday->holiday_name}}"
                                            data-from_date="{{ $holiday->from_date }}"
                                            data-to_date="{{ $holiday->to_date }}"
                                            data-days_count="{{ $holiday->days_count }}"
                                            data-description="{{ htmlentities($holiday->description) }}"
                                            data-status="{{ $holiday->status }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit-holiday">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" 
                                             class="delete-btn" 
                                            data-id="{{ $holiday->id }}"
                                            data-bs-target="#delete-modal" data-bs-toggle="modal">
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
        <p class="mb-0">2014 - 2025 &copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const holiday_name = this.dataset.holiday_name;
                const from_date = this.dataset.from_date;
                const to_date = this.dataset.to_date;
                const days_count = this.dataset.days_count;
                const description = this.dataset.description;
                const status = this.dataset.status;
        
                document.getElementById('edit-id').value = id;
                document.getElementById('holiday_name').value = holiday_name;
                document.getElementById('from_date').value = from_date;
                document.getElementById('to_date').value = to_date;
                document.getElementById('days_count').value = days_count;
                $('#summernote2').summernote('code', description);
                document.getElementById('edit-status').checked = status == 1;

                // Set form action dynamically
                document.getElementById('editForm').action = `/holiday/${id}`;
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
@endsection
