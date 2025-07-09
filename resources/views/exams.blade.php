@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
  <div class="content">
    <div class="page-header d-flex justify-content-between">
      <div>
        <h4>Manage Exams</h4>
        <h6>Manage your examinations</h6>
      </div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-exam">
        <i class="ti ti-plus"></i> Add Exam
      </button>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Exam Name</th>
                <th>Term</th>
                <th>Classes/Subjects</th>
                <th>Status</th>
                <th>Analysed</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($exams as $index => $exam)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $exam->name }}</td>
                <td>{{ $exam->term->term_name ?? 'N/A' }}</td>
                <td>
                  @foreach($exam->examSubjectsClasses->groupBy('school_class_id') as $classId => $items)
                    @php
                      $class = \App\Models\SchoolClass::with('level','stream')->find($classId);
                    @endphp
                    @if($class)
                      <div class="mb-1">
                        <strong>{{ $class->level->level_name }} {{ $class->stream->name }}</strong>
                        <br>
                        @foreach($items->pluck('subject.subject_name')->unique() as $subject)
                          <span class="badge text-white ">{{ $subject }}</span>
                        @endforeach
                      </div>
                    @endif
                  @endforeach
                </td>
                <td>
                  <span class="badge bg-{{ $exam->status ? 'success' : 'danger' }}">{{ $exam->status ? 'Active' : 'Inactive' }}</span>
                </td>
                <td>
                  <span class="badge bg-{{ $exam->is_analysed ? 'primary' : 'warning' }}">{{ $exam->is_analysed ? 'Yes' : 'No' }}</span>
                </td>
                <td>
                  <button class="btn btn-sm btn-primary edit-btn"
                          data-id="{{ $exam->id }}"
                          data-name="{{ $exam->name }}"
                          data-term_id="{{ $exam->term_id }}"
                          data-status="{{ $exam->status }}"
                          data-is_analysed="{{ $exam->is_analysed }}"
                          data-map='@json($exam->examSubjectsClasses->map(function($item){
                            return [
                              "class_id" => $item->school_class_id,
                              "subject_id" => $item->subject_id
                            ];
                          }))'
                          data-bs-toggle="modal"
                          data-bs-target="#edit-exam">
                    Edit
                  </button>
                  <button class="btn btn-sm btn-danger delete-btn"
                          data-id="{{ $exam->id }}"
                          data-name="{{ $exam->name }}"
                          data-bs-toggle="modal"
                          data-bs-target="#delete-confirmation">
                    Delete
                  </button>
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

<!-- ADD MODAL -->
<div class="modal fade" id="add-exam" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form action="{{ route('exams.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Exam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-12">
          <label>Exam Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label>Term</label>
          <select name="term_id" class="form-select" required>
            @foreach($terms as $term)
            <option value="{{ $term->id }}">{{ $term->term_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label>Classes</label>
          <div class="border p-2 rounded" id="class-checkboxes" style="max-height:200px;overflow:auto;">
            @foreach($classes as $class)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="class_ids[]" value="{{ $class->id }}">
              <label class="form-check-label">
                {{ $class->level->level_name }} {{ $class->stream->name }}
              </label>
            </div>
            @endforeach
          </div>
        </div>
        <div class="col-md-3">
          <label>Status</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="status" value="1"> Active
          </div>
        </div>
        <div class="col-md-3">
          <label>Analysed</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_analysed" value="1"> Analysed
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="edit-exam" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="edit-exam-form" method="POST" class="modal-content">
      @csrf @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Exam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-12">
          <label>Exam Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label>Term</label>
          <select name="term_id" class="form-select" required>
            @foreach($terms as $term)
            <option value="{{ $term->id }}">{{ $term->term_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label>Classes</label>
          <div class="border p-2 rounded" id="edit-class-checkboxes" style="max-height:200px;overflow:auto;">
            @foreach($classes as $class)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="class_ids[]" value="{{ $class->id }}">
              <label class="form-check-label">
                {{ $class->level->level_name }} {{ $class->stream->name }}
              </label>
            </div>
            @endforeach
          </div>
        </div>
        <div class="col-md-3">
          <label>Status</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="status" value="1"> Active
          </div>
        </div>
        <div class="col-md-3">
          <label>Analysed</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_analysed" value="1"> Analysed
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="delete-confirmation" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <form id="delete-form" method="POST" class="modal-content">
      @csrf @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title text-danger">Delete Exam</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete-exam-name"></strong>?</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- SUBJECT SELECTION MODAL -->
<div class="modal fade" id="subject-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Subjects</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row" id="subject-modal-body">
        <div class="text-muted">Loading subjects...</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="save-subject-selection" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const subjectModal = new bootstrap.Modal(document.getElementById('subject-modal'));
  const subjectModalBody = document.getElementById('subject-modal-body');
  let selectedSubjects = {};
  let currentClassId = null;

  function setupClassCheckboxes(containerId, isEdit = false) {
    const classCheckboxes = document.querySelectorAll(`${containerId} input[name="class_ids[]"]`);
    classCheckboxes.forEach(cb => {
      cb.addEventListener('change', function() {
        if (cb.checked) {
          currentClassId = cb.value;
          fetch(`/classes/${currentClassId}/subjects`)
            .then(res => res.json())
            .then(data => {
              let html = "";
              data.forEach(subject => {
                const isChecked = selectedSubjects[currentClassId]?.includes(subject.id) ? "checked" : "";
                html += `
                  <div class="form-check col-md-4">
                    <input class="form-check-input" type="checkbox" value="${subject.id}" ${isChecked}>
                    <label class="form-check-label">${subject.subject_name}</label>
                  </div>
                `;
              });
              subjectModalBody.innerHTML = html;
              subjectModal.show();
            });
        } else {
          delete selectedSubjects[cb.value];
        }
      });
    });
  }

  setupClassCheckboxes('#class-checkboxes');
  setupClassCheckboxes('#edit-class-checkboxes', true);

  document.querySelector('#add-exam form').addEventListener('submit', function() {
    for (const [classId, subjectIds] of Object.entries(selectedSubjects)) {
      subjectIds.forEach(subjectId => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'subject_class_map[]';
        hidden.value = `${classId}:${subjectId}`;
        this.appendChild(hidden);
      });
    }
  });

  document.querySelector('#edit-exam-form').addEventListener('submit', function() {
    for (const [classId, subjectIds] of Object.entries(selectedSubjects)) {
      subjectIds.forEach(subjectId => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'subject_class_map[]';
        hidden.value = `${classId}:${subjectId}`;
        this.appendChild(hidden);
      });
    }
  });

  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = btn.dataset.id;
      const name = btn.dataset.name;
      const termId = btn.dataset.term_id;
      const status = btn.dataset.status == 1;
      const isAnalysed = btn.dataset.is_analysed == 1;
      const map = JSON.parse(btn.dataset.map);

      document.querySelector('#edit-exam-form').action = `/exams/${id}`;
      document.querySelector('#edit-exam-form input[name="name"]').value = name;
      document.querySelector('#edit-exam-form select[name="term_id"]').value = termId;
      document.querySelector('#edit-exam-form input[name="status"]').checked = status;
      document.querySelector('#edit-exam-form input[name="is_analysed"]').checked = isAnalysed;

      selectedSubjects = {};
      document.querySelectorAll('#edit-class-checkboxes input[name="class_ids[]"]').forEach(cb => {
        cb.checked = false;
      });

      map.forEach(item => {
        if (!selectedSubjects[item.class_id]) selectedSubjects[item.class_id] = [];
        selectedSubjects[item.class_id].push(item.subject_id);
        const checkbox = document.querySelector(`#edit-class-checkboxes input[value="${item.class_id}"]`);
        if (checkbox) checkbox.checked = true;
      });
    });
  });

  document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = btn.dataset.id;
      const name = btn.dataset.name;
      document.querySelector('#delete-form').action = `/exams/${id}`;
      document.querySelector('#delete-exam-name').textContent = name;
    });
  });

});
</script>
@endsection
