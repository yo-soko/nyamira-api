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
                    <h4 class="fw-bold">Vehicles Meter History</h4>
                    <h6>Manage your Vehicle meter history</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#meterHistoryModal">
                    <i class="ti ti-circle-plus me-1"></i> Add Reading
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
                                <th>Reading (Km)</th>
                                <th>Source</th>
                                <th>Recorded At</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $history)
                                <tr>
                                    <td>{{ $history->vehicle->name }} ({{ $history->vehicle->license_plate }})</td>
                                    <td>{{ $history->operator?->name ?? 'Unassigned' }}</td>
                                    <td>{{ number_format($history->meter_reading, 0) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $history->source === 'gps' ? 'info' : 'secondary' }}">
                                            {{ ucfirst($history->source) }}
                                        </span>
                                    </td>
                                    <td>{{ $history->recorded_at->format('d M Y H:i') }}</td>
                                    <td>{{ $history->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                              
                            @endforelse
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

<div class="modal fade" id="meterHistoryModal" tabindex="-1" aria-labelledby="meterHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('meter-histories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="meterHistoryModalLabel">Add Meter Reading</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="vehicle_id" class="form-label">Vehicle</label>
                    <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                        <option value="">-- Select Vehicle --</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->license_plate }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="meter_reading" class="form-label">Meter Reading (Km)</label>
                    <input type="number" name="meter_reading" id="meter_reading" class="form-control" step="0.1" required>
                </div>

                <div class="mb-3">
                    <label for="source" class="form-label">Source</label>
                    <select name="source" id="source" class="form-select" required>
                        <option value="manual">Manual</option>
                        <option value="gps">GPS</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="recorded_at" class="form-label">Recorded At</label>
                    <input type="datetime-local" name="recorded_at" id="recorded_at" class="form-control">
                    <small class="text-muted">Leave blank to use current date/time</small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Reading</button>
            </div>
        </form>
    </div>
</div>

@endsection
@endhasanyrole
