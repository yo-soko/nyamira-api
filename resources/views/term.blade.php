<?php $page = 'term'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">

        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Terms</h4>
                    <h6>Manage your Terms</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{ asset('build/img/icons/pdf.svg') }}" alt="pdf"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('build/img/icons/excel.svg') }}" alt="excel"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTermModal">
                    <i class="ti ti-circle-plus me-1"></i>Add Term
                </a>
            </div>
        </div>

        {{-- Term Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Term Name</th>
                            <th>Year</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($terms as $term)
                        <tr>
                            <td>{{ $term->term_name }}</td>
                            <td>{{ $term->year }}</td>
                            <td>{{ \Carbon\Carbon::parse($term->start_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($term->end_date)->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $term->status ? 'success' : 'secondary' }}">
                                    {{ $term->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTermModal{{ $term->id }}">Edit</button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTermModal{{ $term->id }}">Delete</button>
                            </td>
                        </tr>

                        @push('modals')
                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editTermModal{{ $term->id }}" tabindex="-1" aria-labelledby="editTermModalLabel{{ $term->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="modal-content" action="{{ route('terms.update', $term->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Term</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Term Name</label>
                                            <input type="text" name="term_name" class="form-control" value="{{ $term->term_name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Year</label>
                                            <input type="number" name="year" class="form-control" value="{{ $term->year }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" name="start_date" class="form-control" value="{{ $term->start_date }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">End Date</label>
                                            <input type="date" name="end_date" class="form-control" value="{{ $term->end_date }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="1" {{ $term->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $term->status == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Update Term</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Delete Modal --}}
                        <div class="modal fade" id="deleteTermModal{{ $term->id }}" tabindex="-1" aria-labelledby="deleteTermModalLabel{{ $term->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form class="modal-content" action="{{ route('terms.destroy', $term->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header text-white">
                                        <h5 class="modal-title" id="deleteTermModalLabel{{ $term->id }}">Delete Confirmation</h5>
                                        <button type="button" class="btn-close btn-close-red" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the term <strong>{{ $term->term_name }}</strong> ({{ $term->year }})?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endpush

                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No terms available.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Term Modal -->
        <div class="modal fade" id="addTermModal" tabindex="-1" aria-labelledby="addTermModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('terms.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Term</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Term Name</label>
                            <input type="text" name="term_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Term</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

{{-- Modals Section --}}
@stack('modals')
@endsection
