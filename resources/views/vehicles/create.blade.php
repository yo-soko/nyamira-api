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
                    <h4 class="fw-bold">Vehicles Section</h4>
                    <h6>Manage your Vehicles</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-vehicle">
                    <i class="ti ti-circle-plus me-1"></i>Add Vehicle
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
                                <th></th>
                                <th>Vehicle</th>
                                <th>License Plate</th>
                                <th>Type</th>
                                <th>Fuel</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                            <tr>
                                <td>
                                    <label class="checkboxs">
                                        <input type="checkbox"><span class="checkmarks"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('vehicles.show', $vehicle->id) }}" class="avatar avatar-md me-2">
                                            <img src="{{ $vehicle->photo ? asset('storage/'.$vehicle->photo) : asset('build/img/cars/default.png') }}" alt="vehicle">
                                        </a>
                                        <a href="{{ route('vehicles.show', $vehicle->id) }}">{{ $vehicle->name }}</a>
                                    </div>
                                </td>
                                <td>{{ $vehicle->license_plate }}</td>
                                <td>{{ $vehicle->type }}</td>
                                <td>{{ $vehicle->fuel_type }}</td>
                                <td>{{ $vehicle->year }}</td>
                                <td>
                                    <span class="badge bg-{{ $vehicle->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($vehicle->status) }}
                                    </span>
                                </td>
                                <td class="action-table-data">
                                    <div class="edit-delete-action">
                                        <a href="javascript:void(0);" class="edit-btn"
                                           data-id="{{ $vehicle->id }}"
                                           data-bs-toggle="modal" data-bs-target="#edit-vehicle">
                                           <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="delete-btn"
                                           data-id="{{ $vehicle->id }}"
                                           data-bs-toggle="modal" data-bs-target="#delete-modal">
                                           <i data-feather="trash-2" class="feather-trash"></i>
                                        </a>
                                    </div>
                                </td>
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
<div class="modal fade" id="add-vehicle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('vehicles._form') {{-- extract the big form into a partial --}}
            </div>
        </div>
    </div>
</div>

@endsection
@endhasanyrole
