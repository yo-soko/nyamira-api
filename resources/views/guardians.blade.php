@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <h4>Guardian List</h4>
          
        </div>

        <!-- Guardian Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead class="thead-light" >
                            <tr>
                                <th>Guardian Name</th>
                                <th>Student</th>
                                <th>Relationship</th>
                                <th>Phones</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guardians as $guardian)
                            <tr>
                                <td>{{ $guardian->guardian_first_name }} {{ $guardian->guardian_last_name }}</td>
                                <td>{{ $guardian->student->first_name ?? ' ' }}{{ $guardian->student->second_name ?? ' ' }}{{ $guardian->student->last_name ?? ' ' }}</td>
                                <td>{{ $guardian->guardian_relationship }}</td>
                                <td>{{ $guardian->first_phone ?? '-' }} / {{ $guardian->second_phone }}</td>
                                <td>{{ $guardian->email }}</td>
                              
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editGuardianModal{{ $guardian->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('guardians.update', $guardian->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Guardian</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Student</label>
                                                    <select name="student_id" class="form-control" required>
                                                        @foreach($students as $student)
                                                            <option value="{{ $student->id }}" {{ $guardian->student_id == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>First Name</label>
                                                    <input type="text" name="guardian_first_name" class="form-control" value="{{ $guardian->guardian_first_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Last Name</label>
                                                    <input type="text" name="guardian_last_name" class="form-control" value="{{ $guardian->guardian_last_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Relationship</label>
                                                    <input type="text" name="guardian_relationship" class="form-control" value="{{ $guardian->guardian_relationship }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>First Phone</label>
                                                    <input type="text" name="first_phone" class="form-control" value="{{ $guardian->first_phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Second Phone</label>
                                                    <input type="text" name="second_phone" class="form-control" value="{{ $guardian->second_phone }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>ID Number</label>
                                                    <input type="text" name="id_number" class="form-control" value="{{ $guardian->id_number }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $guardian->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Address</label>
                                                    <textarea name="address" class="form-control">{{ $guardian->address }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label>About</label>
                                                    <textarea name="guardian_about" class="form-control">{{ $guardian->guardian_about }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary">Update</button>
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
        <!-- Add Guardian Modal -->
        <div class="modal fade" id="addGuardianModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('guardians.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Guardian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Student</label>
                                <select name="student_id" class="form-control" required>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>First Name</label>
                                <input type="text" name="guardian_first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Last Name</label>
                                <input type="text" name="guardian_last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Relationship</label>
                                <input type="text" name="guardian_relationship" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>First Phone</label>
                                <input type="text" name="first_phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Second Phone</label>
                                <input type="text" name="second_phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>ID Number</label>
                                <input type="text" name="id_number" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>About</label>
                                <textarea name="guardian_about" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
