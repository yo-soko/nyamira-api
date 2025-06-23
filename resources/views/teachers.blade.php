@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
           <div class="page-title">
                    <h4>Teachers</h4>
                    <h6>Manage your Teachers</h6>
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
             @hasanyrole('admin|developer|manager|director|supervisor|class_teacher')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teacherModal">Add Teacher</button>
            @endhasrole
        </div>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Qualification</th>
                            <th>Department</th>
                            <th>Subjects</th>
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
                            <td>{{ $teacher->department->name }}</td>
                            <td>
                                @if($teacher->subjects->count() > 0)
                                    {{ $teacher->subjects->pluck('subject_name')->join(', ') }}
                                @else
                                    <em>No subjects assigned</em>
                                @endif
                            </td>
                            <td>{{ $teacher->status == 1 ? 'Active' : 'Inactive' }}</td>
                            <td>
                            
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>   
    </div>
</div>
    <script>
        let index = 1;
        function addEntry() {
            const container = document.getElementById('teaching-area');
            const div = document.createElement('div');
            div.className = 'teaching-entry';
            div.innerHTML = `
                <select name="subject_class[${index}][subject_id]">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>

                <select name="subject_class[${index}][schoolclass_id]">
                    @foreach($schoolclasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            `;
            container.appendChild(div);
            index++;
        }
    </script>
<!-- Teacher Add/Edit Modal -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <form id="teacherForm" method="POST" action="{{ route('teachers.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="teacherModalLabel">Add/Edit Teacher</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label for="first_name" class="form-label">Teacher's First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
            </div>

            <div class="col-md-6">
              <label for="last_name" class="form-label">Teacher's Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
            </div>

            <div class="col-md-6">
              <label for="date_of_birth" class="form-label">Date of Birth</label>
              <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
            </div>

            <div class="col-md-6">
              <label for="id_no" class="form-label">ID Number</label>
              <input type="text" class="form-control" id="id_no" name="id_no" placeholder="Enter identity number" required>
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Address</label>
              <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter address" required></textarea>
            </div>

            <div class="col-md-6">
              <label for="education_level" class="form-label">Education Level</label>
              <input type="text" class="form-control" id="education_level" name="education_level" placeholder="Enter education level" required>
            </div>

            <div class="col-md-6">
              <label for="years_of_experience" class="form-label">Years of Experience</label>
              <input type="number" min="0" class="form-control" id="years_of_experience" name="years_of_experience" placeholder="Enter experience years" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select class="form-select" id="gender" name="gender" required>
                <option value="" selected disabled>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="department" class="form-label">Department</label>
              <select class="form-select" id="department" name="department" required>
                <option value="" selected disabled>Select Department</option>
                @foreach($departments as $dept)
                  <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Subjects to Teach</label>
              <div class="row">
                @foreach($subjects as $subject)
                <div class="col-md-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="subjects[]" id="subject_{{ $subject->id }}" value="{{ $subject->id }}">
                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                      {{ $subject->subject_name }}
                    </label>
                  </div>
                </div>
                @endforeach
              </div>
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Teacher</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection
