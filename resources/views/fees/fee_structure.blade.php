@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
    @if(session('success'))
        @include('layout.toast', ['type' => 'success', 'message' => session('success')])
    @endif

        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Fee Structure</h4>
                <h6>Manage School Fee Structure</h6>
            </div>
            @hasanyrole('admin|developer|manager|director|supervisor')
            <div class="page-btn">
                <button class="btn btn-primary" id="addFeeStructureBtn" data-bs-toggle="modal" data-bs-target="#feeStructureModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Fee Structure
                </button>
            </div>
            @endhasanyrole
        </div>

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Fee Structure</h5>

                <div>
                    <a href="#" data-bs-toggle="tooltip" title="PDF"><img src="{{ asset('build/img/icons/pdf.svg') }}" alt="PDF"></a>
                    <a href="#" data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('build/img/icons/excel.svg') }}" alt="Excel"></a>
                    <a href="#" data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a>
                </div>
            </div>

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
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <th>Date Created</th>
                                <th class="text-end">Actions</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feeStructures as $index => $fee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fee->classLevel->level_name ?? 'No Class Level' }}</td>
                                <td>{{ $fee->term->term_name ?? 'No Term' }}</td>
                                <td>KSh {{ number_format($fee->amount) }}</td>

                                <td>{{ $fee->description }}</td>
                                <td>
                                    <span class="badge bg-{{ $fee->status == 'active' ? 'danger' : 'success' }}">
                                        {{ ucfirst($fee->status) }}
                                    </span>
                                </td>
                                @hasanyrole('admin|developer|manager|director|supervisor')
                                <td>{{ \Carbon\Carbon::parse($fee->created_at)->format('d M Y') }}</td>
                                <td class="text-end">
                                    {{-- Blade-controlled modal triggers --}}
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editFeeModal{{ $fee->fee_id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFeeModal{{ $fee->fee_id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                                @endhasanyrole
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editFeeModal{{ $fee->fee_id }}" tabindex="-1" aria-labelledby="editFeeModalLabel{{ $fee->fee_id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('fee-structure.update') }}">
                                        @csrf
                                        <input type="hidden" name="fee_id" value="{{ $fee->fee_id }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Fee Structure</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-12">
                                                    <label>Class Level</label>
                                                    <select name="level_id" class="form-control" required>
                                                        @foreach($classLevels as $class)
                                                            <option value="{{ $class->id }}" {{ $fee->level_id == $class->id ? 'selected' : '' }}>
                                                                {{ $class->level_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Term</label>
                                                    <select name="term_id" class="form-control" required>
                                                        @foreach($terms as $term)
                                                            <option value="{{ $term->id }}" {{ $fee->term_id == $term->id ? 'selected' : '' }}>
                                                                {{ $term->term_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Amount (KSh)</label>
                                                    <input type="number" name="amount" class="form-control" value="{{ $fee->amount }}" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Description</label>
                                                    <input type="text" name="description" class="form-control" value="{{ $fee->description }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Status</label>
                                                    <select name="feeStatus" class="form-control" required>
                                                        <option value="active" {{ $fee->status === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $fee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update Fee</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteFeeModal{{ $fee->fee_id }}" tabindex="-1" aria-labelledby="deleteFeeModalLabel{{ $fee->fee_id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('fee-structure.delete') }}">
                                        @csrf
                                        <input type="hidden" name="fee_id" value="{{ $fee->fee_id }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Fee Structure</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete the fee for
                                                <strong>{{ $fee->classLevel->level_name ?? 'N/A' }}</strong>
                                                - <strong>{{ $fee->term->term_name ?? 'N/A' }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>



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
</div>

<!-- Fee Structure Modal -->
<div class="modal fade" id="feeStructureModal" tabindex="-1" aria-labelledby="feeStructureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="feeStructureForm" method="POST" action="{{ route('fee-structure.store') }}">
            @csrf
            <input type="hidden" name="fee_id" id="fee_id">
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
                                <option value="{{ $class->id }}">{{ $class->level_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Term</label>
                        <select name="term_id" class="form-control" required>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->term_name }}</option>
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
