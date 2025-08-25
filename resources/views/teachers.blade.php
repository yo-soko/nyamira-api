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
                                <th>Actions</th>
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
                                <td>
                                    <div class="d-flex gap-2">
                                        <button
                                            class="btn btn-sm btn-info"
                                            title="Edit"
                                            onclick='editTeacher(@json($teacher))'>
                                            <i class="ti ti-pencil"></i>
                                        </button>

                                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-danger delete-btn"
                                                    data-id="{{ $teacher->id }}"
                                                    data-name="{{ $teacher->first_name }} {{ $teacher->last_name }}"
                                                    title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>

                                        </form>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
      <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>

  </div>
</div>


<!-- Teacher Add Modal -->
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
              <div id="teaching-area" class="teaching-area">
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

<!-- Single Edit Modal (replace the per-teacher modal loop with this) -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <!-- IMPORTANT: form id and method here -->
    <form id="editTeacherForm" method="POST" action="">
      @csrf
      @method('PUT')
      <input type="hidden" name="teacher_id" id="edit_teacher_id">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="edit_first_name" class="form-label">First Name</label>
              <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
            </div>

            <div class="col-md-6">
              <label for="edit_last_name" class="form-label">Last Name</label>
              <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
            </div>

            <div class="col-md-6">
              <label for="edit_date_of_birth" class="form-label">Date of Birth</label>
              <input type="date" class="form-control" name="date_of_birth" id="edit_date_of_birth" required>
            </div>

            <div class="col-md-6">
              <label for="edit_email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="edit_email" required>
            </div>

            <div class="col-md-6">
              <label for="edit_phone" class="form-label">Phone</label>
              <input type="text" class="form-control" name="phone" id="edit_phone" required>
            </div>

            <div class="col-md-6">
              <label for="edit_id_no" class="form-label">ID Number</label>
              <input type="text" class="form-control" name="id_no" id="edit_id_no" required>
            </div>

            <div class="col-12">
              <label for="edit_address" class="form-label">Address</label>
              <textarea class="form-control" name="address" id="edit_address" rows="2" required></textarea>
            </div>

            <div class="col-md-6">
              <label for="edit_education_level" class="form-label">Education Level</label>
              <input type="text" class="form-control" name="education_level" id="edit_education_level" required>
            </div>

            <div class="col-md-6">
              <label for="edit_years_of_experience" class="form-label">Years of Experience</label>
              <input type="number" class="form-control" name="years_of_experience" id="edit_years_of_experience" min="0" required>
            </div>

            <div class="col-md-6">
              <label for="edit_gender" class="form-label">Gender</label>
              <select class="form-select" name="gender" id="edit_gender" required>
                <option disabled value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_department" class="form-label">Department</label>
              <select name="department" id="edit_department" class="form-select" required>
                <option disabled value="">Select Department</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_status" class="form-label">Status</label>
              <select name="status" id="edit_status" class="form-select" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

            {{-- Teaching area will be injected here (note the class "teaching-area") --}}
            <div class="col-12">
                <label class="form-label">Subjects and Classes to Teach</label>
                <div id="edit-teaching-area" class="teaching-area">
                    {{-- HTML from /teachers/{id}/subjects will be injected here by AJAX --}}
                </div>
            </div>
          </div> <!-- row -->
        </div> <!-- modal-body -->

        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" type="submit">Update</button>
        </div>
      </div> <!-- modal-content -->
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-labelledby="deleteTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTeacherModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close bg-white text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="teacherName"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form id="deleteTeacherForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>





<script>
let teachingIndex = 1;
let editTeachingIndex = 1;

document.addEventListener('click', function(e) {
    const addBtn = e.target.closest('.add-subject-class');
    if (addBtn) {
        const modal = addBtn.closest('.modal');
        let container;
        let index;

        if (modal.querySelector('#edit-teaching-area')) {
            container = modal.querySelector('#edit-teaching-area');
            index = editTeachingIndex++;
        } else {
            container = modal.querySelector('.teaching-area');
            index = teachingIndex++;
        }

        const html = `
        <div class="row align-items-end teaching-entry mb-2">
            <div class="col-md-5">
                <select name="subject_class[${index}][subject_id]" class="form-select" required>
                    <option value="" disabled selected>Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="subject_class[${index}][class_id]" class="form-select" required>
                    <option value="" disabled selected>Select Class</option>
                    @foreach($schoolclasses as $class)
                        <option value="{{ $class->id }}">{{ $class->level?->level_name }} {{ $class->stream?->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-subject-class"><i class="ti ti-minus"></i></button>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', html);
    }

    const removeBtn = e.target.closest('.remove-subject-class');
    if (removeBtn) {
        const entry = removeBtn.closest('.teaching-entry');

        // If editing an existing teacher, mark for deletion instead of losing the ID
        const hiddenId = entry.querySelector('input[name="existing_id"]');
        if (hiddenId) {
            // Add a hidden input to track IDs to delete
            let form = removeBtn.closest('form');
            form.insertAdjacentHTML('beforeend', `<input type="hidden" name="delete_subject_class_ids[]" value="${hiddenId.value}">`);
        }

        entry.remove();
    }
});


//deleting modal

document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.delete-btn');
    if (deleteBtn) {
        const teacherId = deleteBtn.getAttribute('data-id');
        const teacherName = deleteBtn.getAttribute('data-name');

        // Set form action
        const form = document.getElementById('deleteTeacherForm');
        form.setAttribute('action', `/teachers/${teacherId}`);

        // Set teacher name in modal
        document.getElementById('teacherName').textContent = teacherName;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('deleteTeacherModal'));
        modal.show();
    }
});


// This function is used by the table edit button:
function editTeacher(teacher) {
    // set hidden id
    $('#edit_teacher_id').val(teacher.id);

    // set the form action to the update route for this teacher
    $('#editTeacherForm').attr('action', '/teachers/' + teacher.id);

    // populate basic fields
    $('#edit_first_name').val(teacher.first_name);
    $('#edit_last_name').val(teacher.last_name);
    $('#edit_date_of_birth').val(teacher.date_of_birth);
    $('#edit_email').val(teacher.email);
    $('#edit_phone').val(teacher.phone);
    $('#edit_id_no').val(teacher.id_no);
    $('#edit_address').val(teacher.address);
    $('#edit_education_level').val(teacher.education_level);
    $('#edit_years_of_experience').val(teacher.years_of_experience);
    $('#edit_gender').val(teacher.gender);
    $('#edit_department').val(teacher.department_id);
    $('#edit_status').val(teacher.status);

    // show loading while we fetch the subject/class rows
    $('#edit-teaching-area').html('<p>Loading subjects and classes...</p>');

    // Reset indexes before loading rows for this teacher
    editTeachingIndex = 0;

    // fetch the partial that contains the pre-selected rows
    $.get(`/teachers/${teacher.id}/subjects`, function(html) {
        // inject HTML
        $('#edit-teaching-area').html(html);

        // set editTeachingIndex to number of existing rows so newly added rows use the next indexes
        editTeachingIndex = $('#edit-teaching-area .teaching-entry').length || 0;
    });

    // show modal
    $('#editTeacherModal').modal('show');
}
</script>





@endsection
