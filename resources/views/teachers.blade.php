@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4>Teachers</h4>
                <h6>Manage your Teachers</h6>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @hasanyrole('admin|developer|manager|director|supervisor|class_teacher')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teacherModal">
                    <i class="ti ti-plus"></i> Add Teacher
                </button>
                @endhasrole
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Qualification</th>
                                <th>Department</th>
                                <th>Subjects & Classes</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                <td>{{ $teacher->phone }}</td>
                                <td>{{ $teacher->gender }}</td>
                                <td>{{ $teacher->education_level }}</td>
                                <td>{{ $teacher->department->name ?? '-' }}</td>
                               <td>
    @if($teacher->subjects->count() > 0)
        <ul class="mb-0 ps-3">
            @foreach($teacher->subjects as $subject)
                @php
                    $class = \App\Models\SchoolClass::find($subject->pivot->class_id);
                @endphp
                <li>
                    {{ $subject->subject_name }}
                    @if($class)
                        (Class: {{ $class->level->level_name ?? ' '}} {{ $class->stream->name ?? ' ' }})
                    @else
                        (Class: Unknown)
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <em>No subjects assigned</em>
    @endif
</td>

                                <td>
                                    <span class="badge {{ $teacher->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $teacher->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>   
    </div>
</div>


<!-- Teacher Add/Edit Modal -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form id="teacherForm" method="POST" action="{{ route('teachers.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="teacherModalLabel">Add Teacher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label for="first_name" class="form-label">First Name</label>
              <input type="text" class="form-control" name="first_name" required>
            </div>

            <div class="col-md-6">
              <label for="last_name" class="form-label">Last Name</label>
              <input type="text" class="form-control" name="last_name" required>
            </div>

            <div class="col-md-6">
              <label for="date_of_birth" class="form-label">Date of Birth</label>
              <input type="date" class="form-control" name="date_of_birth" required>
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control" name="phone" required>
            </div>

            <div class="col-md-6">
              <label for="id_no" class="form-label">ID Number</label>
              <input type="text" class="form-control" name="id_no" required>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" name="address" rows="2" required></textarea>
            </div>

            <div class="col-md-6">
              <label for="education_level" class="form-label">Education Level</label>
              <input type="text" class="form-control" name="education_level" required>
            </div>

            <div class="col-md-6">
              <label for="years_of_experience" class="form-label">Years of Experience</label>
              <input type="number" class="form-control" name="years_of_experience" min="0" required>
            </div>

            <div class="col-md-6">
              <label for="gender" class="form-label">Gender</label>
              <select class="form-select" name="gender" required>
                <option disabled selected value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="department" class="form-label">Department</label>
              <select name="department" class="form-select" required>
                <option disabled selected value="">Select Department</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Subjects and Classes to Teach</label>
              <div id="teaching-area">
                <div class="row align-items-end teaching-entry mb-2">
                    <div class="col-md-5">
                        <select name="subject_class[0][subject_id]" class="form-select" required>
                            <option value="" disabled selected>Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="subject_class[0][class_id]" class="form-select" required>
                            <option value="" disabled selected>Select Class</option>
                            @foreach($schoolclasses as $class)
                                <option value="{{ $class->id }}">{{ $class->level->level_name ?? ' '}} {{ $class->stream->name ?? ' ' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success add-subject-class"><i class="ti ti-plus"></i></button>
                    </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
    let teachingIndex = 1;
    document.addEventListener('click', function(e) {
        if(e.target.closest('.add-subject-class')) {
            const container = document.getElementById('teaching-area');
            const html = `
            <div class="row align-items-end teaching-entry mb-2">
                <div class="col-md-5">
                    <select name="subject_class[${teachingIndex}][subject_id]" class="form-select" required>
                        <option value="" disabled selected>Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="subject_class[${teachingIndex}][class_id]" class="form-select" required>
                        <option value="" disabled selected>Select Class</option>
                        @foreach($schoolclasses as $class)
                            <option value="{{ $class->id }}">{{ $class->level->level_name }} {{ $class->stream->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-subject-class"><i class="ti ti-minus"></i></button>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            teachingIndex++;
        }
        if(e.target.closest('.remove-subject-class')) {
            e.target.closest('.teaching-entry').remove();
        }
    });
</script>
@endsection
