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
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
                    <i class="ti ti-circle-plus me-1"></i> Create Expense
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
                                <th>Type</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Frequency</th>
                                <th>Date</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->vehicle?->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($expense->expense_type) }}</td>
                                <td>{{ $expense->vendor?->name ?? 'N/A' }}</td>
                                <td>KSh {{ number_format($expense->amount, 2) }}</td>
                                <td>{{ ucfirst($expense->frequency) }}</td>
                                <td>{{ $expense->date }}</td>
                                <td>{{ $expense->notes }}</td>
                            </tr>
                            @empty
                              
                            @endforelse
                        </tbody>
                    </table>
                      {{ $expenses->links() }}
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

<div class="modal fade" id="createExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label>Vehicle *</label>
                        <select name="vehicle_id" class="form-control">
                            <option value="">-- Please select --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Expense Type *</label>
                        <select name="expense_type" class="form-control" required>
                            <option value="">-- Select type --</option>
                            <option>Annual Inspection Fee</option>
                            <option>Depreciation</option>
                            <option>Downpayment</option>
                            <option>Fines</option>
                            <option>Insurance</option>
                            <option>Equipment</option>
                            <option>Lease</option>
                            <option>Loan</option>
                            <option>Miscellaneous</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Vendor</label>
                        <select name="vendor_id" class="form-control">
                            <option value="">-- Please select --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Amount *</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label>Frequency *</label>
                        <select name="frequency" class="form-control" required>
                            <option value="single">Single Expense</option>
                            <option value="monthly">Recurring (Monthly)</option>
                            <option value="annual">Recurring (Annual)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Date *</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Photos</label>
                        <input type="file" name="photos[]" class="form-control" multiple>
                    </div>

                    <div class="mb-3">
                        <label>Documents</label>
                        <input type="file" name="documents[]" class="form-control" multiple>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Expense Entry</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@endhasanyrole
