<?php $page = 'streams'; ?>

@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">  
                <div class="page-title">
                    <h4>Streams</h4>
                    <h6>Manage your Streams</h6>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                    </li>						
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                    </li>
                </ul>
            <!-- Button to trigger Add Stream Modal -->
            <div class="page-btn ">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-holiday"><i class="ti ti-circle-plus me-1"></i>Add Stream</a>

            </div>
        </div>
      <!-- Add Stream Modal -->
      <!-- Add Stream Modal -->
        <div class="modal fade" id="add-holiday" tabindex="-1" aria-labelledby="addStreamLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('streams.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStreamLabel">Add Stream</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="initials" class="form-label">Initials</label>
                                <input type="text" name="initials" class="form-control" placeholder="e.g. ST1" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Stream Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Science Stream" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Stream</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


      <!-- Streams Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light" >
                            <tr>
                                <th>#</th>
                                <th>Initials</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($streams as $key => $stream)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $stream->initials }}</td>
                                <td>{{ $stream->name }}</td>
                                <td>{{ $stream->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm  btn-info" data-bs-toggle="modal" data-bs-target="#editStream{{ $stream->id }}">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStream{{ $stream->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Stream Modal -->
                            <div class="modal fade" id="editStream{{ $stream->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('streams.update', $stream->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Stream</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="text" name="initials" class="form-control mb-2" value="{{ $stream->initials }}" required>
                                                <input type="text" name="name" class="form-control" value="{{ $stream->name }}" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Stream Modal -->
                            <div class="modal fade" id="deleteStream{{ $stream->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('streams.destroy', $stream->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Delete Stream</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete "<strong>{{ $stream->name }}</strong>"?
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>  
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
        </div>
    </div>
</div>
@endsection
