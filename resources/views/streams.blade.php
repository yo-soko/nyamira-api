@extends('layout.mainlayout')

@section('content')
<div class="container mt-4">
    <h4>Streams</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Stream Form -->
    <form action="{{ route('streams.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-2"><input type="text" name="initials" class="form-control" placeholder="Initials" required></div>
            <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Stream Name" required></div>
            <div class="col-md-2"><button class="btn btn-success">Add Stream</button></div>
        </div>
    </form>

    <!-- Streams Table -->
    <table class="table table-bordered">
        <thead>
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
                    <!-- Edit Button triggers modal -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editStream{{ $stream->id }}">Edit</button>

                    <!-- Delete Modal Trigger -->
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteStream{{ $stream->id }}">Delete</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editStream{{ $stream->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('streams.update', $stream->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Edit Stream</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
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

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteStream{{ $stream->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('streams.destroy', $stream->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header"><h5>Delete Stream</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                Are you sure you want to delete "{{ $stream->name }}"?
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
@endsection
