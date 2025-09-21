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
                    <h4 class="fw-bold">Charging Histories</h4>
                    <h6>Manage your Vehicles charging histories</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chargingModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Charging Entry
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
                                <th>Vendor</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Energy (kWh)</th>
                                <th>Cost (KSh)</th>
                                <th>Flags</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chargingHistories as $entry)
                                <tr>
                                    <td>{{ $entry->vehicle->name }} ({{ $entry->vehicle->license_plate }})</td>
                                    <td>{{ $entry->vendor_id ?? '-' }}</td>
                                    <td>{{ $entry->charging_started->format('d M Y, h:i A') }}</td>
                                    <td>{{ $entry->charging_ended ? $entry->charging_ended->format('d M Y, h:i A') : '-' }}</td>
                                    <td>{{ $entry->total_energy }} kWh</td>
                                    <td>KSh {{ number_format($entry->energy_cost, 2) }}</td>
                                    <td>@if($entry->is_personal)<span class="badge bg-warning">Personal</span>@endif</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                        {{ $chargingHistories->links() }}
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

<div class="modal fade" id="chargingModal" tabindex="-1" aria-labelledby="chargingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('charging_histories.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Add Charging Entry</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
            <div class="col-md-6">
                <label>Vehicle *</label>
                <select name="vehicle_id" class="form-select" required>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->license_plate }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label>Odometer (km)</label>
                <input type="number" name="odometer" class="form-control">
            </div>
            <div class="col-md-6">
                <label>Vendor</label>
                <select name="vendor_id" class="form-select">
                    <option value="">-- Select Vendor --</option>
                   
                        <option value="Shell">Shell</option>
                        <option value="Total">Total</option>
                        <option value="Tosha">Tosha</option>
                    
                </select>
            </div>
            <div class="col-md-6">
                <label>Reference</label>
                <input type="text" name="reference" class="form-control" placeholder="Invoice / transaction ID">
            </div>
            <div class="col-md-6">
                <label>Charging Started *</label>
                <input type="datetime-local" name="charging_started" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Charging Ended</label>
                <input type="datetime-local" name="charging_ended" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Total Energy (kWh)</label>
                <input type="number" step="0.01" name="total_energy" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Energy Price (KSh/kWh)</label>
                <input type="number" step="0.01" name="energy_price" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Energy Cost (KSh)</label>
                <input type="number" step="0.01" name="energy_cost" class="form-control">
            </div>
            <div class="col-md-12">
                <label>Flags</label><br>
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="is_personal" value="1" class="form-check-input">
                    <label class="form-check-label">Personal</label>
                </div>
            </div>
            <div class="col-md-6">
                <label>Photos</label>
                <input type="file" name="photos[]" class="form-control" multiple>
            </div>
            <div class="col-md-6">
                <label>Documents</label>
                <input type="file" name="documents[]" class="form-control" multiple>
            </div>
            <div class="col-md-12">
                <label>Comments</label>
                <textarea name="comments" class="form-control"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Charging Entry</button>
        </div>
    </form>
  </div>
</div>

@endsection
@endhasanyrole
