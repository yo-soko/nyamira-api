<?php $page = 'leave-types'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
    <div class="page-wrapper d-flex flex-column">
        <div class="content flex-grow-1">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Leave Type</h4>
                        <h6>Manage your Leaves</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li class="me-2">
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-leave">Add Leave Type</a>
                </div>
            </div>
            <!-- /product list -->
            <div class="card">
                <div class="card-body">
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
                                    <th>Leave Type</th>
                                    <th>Leave Quota</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveTypes as $leave)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $leave->id }}">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $leave->type }}</td>
                                    <td>{{ $leave->quota }}</td>
                                    <td>{{ $leave->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $leave->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $leave->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-btn" href="#" 
                                            data-id="{{ $leave->id }}"
                                            data-type="{{ $leave->type }}"
                                            data-quota="{{ $leave->quota }}"
                                            data-status="{{ $leave->status }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit-leave">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" 
                                             class="delete-btn" 
                                            data-id="{{ $leave->id }}"
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
            <p class="mb-0"> &copy; JavaPA. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const type = this.dataset.type;
                const quota = this.dataset.quota;
                const status = this.dataset.status;
        
                document.getElementById('edit-id').value = id;
                document.getElementById('type').value = type;
                document.getElementById('quota').value = quota;
                document.getElementById('edit-status').checked = status == 1;

                // Set form action dynamically
                document.getElementById('editForm').action = `/leave-type/${id}`;
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
