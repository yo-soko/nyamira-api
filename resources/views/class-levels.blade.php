<?php $page = 'class-levels'; ?>

@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h4>Class Levels</h4>
                <h6>Manage class levels</h6>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassLevelModal">
                <i class="fas fa-plus"></i> Add Class Level
            </button>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Level Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($levels as $key => $level)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $level->level_name }}</td>
                                <td>
                                    @if($level->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Edit button --}}
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editClassLevelModal{{ $level->id }}">
                                        Edit
                                    </button>

                                    {{-- Delete button --}}
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $level->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editClassLevelModal{{ $level->id }}" tabindex="-1" aria-labelledby="editClassLevelModalLabel{{ $level->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form method="POST" action="{{ route('class-levels.update', $level->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Class Level</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Level Name</label>
                                                    <input type="text" name="level_name" class="form-control" value="{{ $level->level_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="1" {{ $level->status == 1 ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ $level->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteModal{{ $level->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $level->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <form action="{{ route('class-levels.destroy', $level->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $level->level_name }}</strong>?
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
                        </tbody>
                    </table>
                </div>  
            </div>
        </div>
    </div>


    {{-- Add Modal --}}
    <div class="modal fade" id="addClassLevelModal" tabindex="-1" aria-labelledby="addClassLevelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('class-levels.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Class Level</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Level Name</label>
                            <input type="text" name="level_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
                <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
                <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
            </div>
</div>
@endsection
