@can('view issue')

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
                    <h4 class="fw-bold">Issues</h4>
                    <h6>Manage issues</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
@can('add issue')

            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createIssueModal">
                    <i class="ti ti-circle-plus me-1"></i> Report Issue
                </a>
            </div>
            @endcan
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
                                <th>Priority</th>
                                <th>Summary</th>
                                <th>Status</th>
                                <th>Reported By</th>
                                <th>Assigned To</th>
                                <th>Reported Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issues as $issue)
                                <tr>
                                    <td>{{ $issue->vehicle->vehicle_name }} ({{ $issue->vehicle->license_plate }})</td>
                                    <td><span class="badge bg-danger">{{ $issue->priority }}</span></td>
                                    <td>{{ $issue->summary }}</td>
                                    <td>{{ $issue->status }}</td>
                                    <td>{{ $issue->reporter->name }}</td>
                                    <td>{{ $issue->assignee->name ?? 'Unassigned' }}</td>
                                    <td>{{ $issue->reported_at }}</td>
                                    <td>{{ $issue->due_date ?? 'N/A' }}</td>
                                </tr>
                            @empty
                              
                            @endforelse
                        </tbody>
                    </table>
                          {{ $issues->links() }}
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

<div class="modal fade" id="createIssueModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('issues.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Report Issue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="vehicle_id" class="form-label">Asset *</label>
                    <select name="vehicle_id" class="form-select" required>
                        <option value="">-- Select Vehicle --</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->license_plate }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Reported Date *</label>
                    <input type="datetime-local" name="reported_at" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Summary *</label>
                    <input type="text" name="summary" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Labels</label>
                    <input type="text" name="labels" class="form-control" placeholder="e.g. Electrical, Engine">
                </div>

                <div class="mb-3">
                    <label>Reported By *</label>
                    <select name="reported_by" class="form-select" required>
                        <option value="">-- Select Reporter --</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Assigned To</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Unassigned --</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Primary Meter Due</label>
                    <input type="number" name="primary_meter_due" class="form-control" placeholder="Enter mileage threshold">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Issue</button>
            </div>
        </form>
    </div>
</div>
@endsection
@endcan
