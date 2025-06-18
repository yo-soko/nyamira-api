@extends('layouts.mainlayout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h4 class="page-title">Fee Structure</h4>
        <button class="btn btn-primary" id="addFeeStructureBtn" data-bs-toggle="modal" data-bs-target="#feeStructureModal">
            <i class="fas fa-plus-circle"></i> Add Fee Structure
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Class</th>
                            <th>Term</th>
                            <th>Amount (KSh)</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeStructures as $index => $fee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $fee->classLevel->name ?? 'N/A' }}</td>
                            <td>{{ $fee->term->name ?? 'N/A' }}</td>
                            <td>{{ number_format($fee->amount, 2) }}</td>
                            <td>{{ $fee->description }}</td>
                            <td>
                                <span class="badge bg-{{ $fee->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($fee->created_at)->format('d M Y') }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-info editFeeStructureBtn" data-id="{{ $fee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger deleteFeeStructureBtn" data-id="{{ $fee->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @if(count($feeStructures) === 0)
                        <tr>
                            <td colspan="8" class="text-center text-muted">No Fee Structures Found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Fee Structure Modal -->
<div class="modal fade" id="feeStructureModal" tabindex="-1" aria-labelledby="feeStructureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="feeStructureForm" method="post">
            @csrf
            <input type="hidden" name="fee_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fee Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-12">
                        <label>Class Level</label>
                        <select name="level_id" class="form-control" required>
                            <option value="">Select Class</option>
                            @foreach($classLevels as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Term</label>
                        <select name="term_id" class="form-control" required>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Amount (KSh)</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label>Status</label>
                        <select name="feeStatus" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="saveFeeStructureBtn" class="btn btn-primary">Save Fee</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/fees.js') }}"></script>
@endpush
