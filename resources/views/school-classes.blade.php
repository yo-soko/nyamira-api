@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

@php
    use App\Models\Stream;
    use App\Models\ClassLevel;
    use App\Models\User;
    use App\Models\Student;

    $streams = Stream::all();
    $levels = ClassLevel::all();
    $teachers = User::where('role', 'teacher')->get(); // adjust if column is different
    $students = Student::all();
@endphp

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Classe</h4>
                    <h6>Manage your Classes</h6>
                </div>						
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
            @hasanyrole('admin|developer|manager|director|supervisor')
            <div class="page-btn">
               <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                <i class="ti ti-plus"></i> Add Class
               </a>
            </div>
             @endhasanyrole
        </div>
        
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    @if($classes->count())
                    <table class="table datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Class Name</th>
                                <th>Teacher</th>
                                <th>Prefect</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $class)
                            <tr>
                                <td>{{ $class->id }}</td>
                                <td>{{ $class->level->level_name ?? 'N/A' }} {{ $class->stream->name ?? 'N/A' }}</td>
                                <td>{{ $class->classTeacher->name ?? 'N/A' }}</td>
                                <td>{{ $class->classPrefect->name ?? 'N/A' }}</td>
                                <td>{{ $class->capacity }}</td>
                                <td>
                                    <span class="badge bg-{{ $class->status ? 'success' : 'danger' }}">
                                        {{ $class->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editClassModal{{ $class->id }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $class->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="p-3">No class records found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- ✅ Place Modals After the Table -->
        @foreach($classes as $class)

            <!-- Edit Modal -->
            <div class="modal fade" id="editClassModal{{ $class->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <form action="{{ route('school-classes.update', $class->id) }}" method="POST" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Class</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="mb-3 col-md-6">
                                <label>Stream</label>
                                <select name="stream_id" class="form-select">
                                    <option value="">Select Stream</option>
                                    @foreach($streams as $stream)
                                    <option value="{{ $stream->id }}" {{ $class->stream_id == $stream->id ? 'selected' : '' }}>
                                        {{ $stream->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Level</label>
                                <select name="level_id" class="form-select">
                                    <option value="">Select Level</option>
                                    @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ $class->level_id == $level->id ? 'selected' : '' }}>
                                        {{ $level->level_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Class Teacher</label>
                                <select name="class_teacher" class="form-select">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $class->class_teacher == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Class Prefect</label>
                                <input type="text" name="class_prefect" class="form-control">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Capacity</label>
                                <input type="number" name="capacity" class="form-control" value="{{ $class->capacity }}">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ $class->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $class->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update Class</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $class->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('school-classes.destroy', $class->id) }}" method="POST" class="modal-content">
                        @csrf @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          Are you sure you want to delete <strong>{{ $class->level->level_name ?? '' }} {{ $class->stream->name ?? '' }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>

        @endforeach


    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('school-classes.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label>Stream</label>
                        <select name="stream_id" class="form-select">
                            <option value="">Select Stream</option>
                            @foreach($streams as $stream)
                                <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Level</label>
                        <select name="level_id" class="form-select">
                            <option value="">Select Level</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->level_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Class Teacher</label>
                        <select name="class_teacher" class="form-select">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Class Prefect</label>
                        <input type="text" name="class_prefect" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Class</button>
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
