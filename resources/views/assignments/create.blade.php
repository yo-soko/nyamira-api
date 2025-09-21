@hasanyrole('admin|developer|manager|director|supervisor')
<?php $page = 'users'; ?>
@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Vehicles Assignment Section</h4>
                    <h6>Manage your Vehicles assignments</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignmentModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Vehicle Assignment
                </a>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Vehicles Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <!-- <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search Vehicle">
                    </div>
                </div> -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>Vehicle</th>
                                <th>Operator</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td>{{ $assignment->vehicle->vehicle_name }} ({{ $assignment->vehicle->license_plate }})</td>
                                    <td>{{ $assignment->operator->name }}</td>
                                    <td>{{ $assignment->start_at ? $assignment->start_at->format('d M Y H:i') : '-' }}</td>
                                    <td>{{ $assignment->end_at ? $assignment->end_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Vehicles Table -->
    </div>

    <!-- Footer -->
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy; All Right Reserved</p>
    </div>
</div>

<!-- Vehicle Form Modal -->
<div class="modal fade" id="assignmentModal" tabindex="-1" role="dialog" aria-labelledby="assignmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Assignment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        @include('assignments._form')
    </div>
  </div>
</div>

@endsection
@endhasanyrole
