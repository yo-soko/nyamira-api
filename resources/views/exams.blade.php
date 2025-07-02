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
                                <th>Subjects</th>
                                <th>Classes</th>
                                <th>Status</th>
                                <th>Analysed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exams as $index => $exam)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $exam->term->term_name ?? 'N/A' }}</td>
                                <td>
                                    @foreach($exam->examSubjectsClasses->pluck('subject.subject_name')->unique() as $subject)
                                        <span class="badge bg-info">{{ $subject }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($exam->examSubjectsClasses->pluck('level.level_name')->unique() as $level)
                                        <span class="badge bg-success">{{ $level }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($exam->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($exam->is_analysed)
                                        <span class="badge bg-primary">Yes</span>
                                    @else
                                        <span class="badge bg-warning">No</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn"
                                        data-id="{{ $exam->id }}"
                                        data-name="{{ $exam->name }}"
                                        data-term_id="{{ $exam->term_id }}"
                                        data-status="{{ $exam->status }}"
                                        data-is_analysed="{{ $exam->is_analysed }}"
                                        data-subjects="{{ json_encode($exam->examSubjectsClasses->pluck('subject_id')->unique()) }}"
                                        data-classes="{{ json_encode($exam->examSubjectsClasses->pluck('level_id')->unique()) }}"
                                        data-bs-toggle="modal" data-bs-target="#edit-exam">
                                        Edit
                                    </button>
                                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
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
                    <label>Subjects</label>
                    <div class="border p-2 rounded" style="max-height:200px;overflow:auto;">
                        @foreach($subjects as $subject)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}">
                            <label class="form-check-label">{{ $subject->subject_name }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Classes</label>
                    <div class="border p-2 rounded" style="max-height:200px;overflow:auto;">
                        @foreach($levels as $level)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="class_ids[]" value="{{ $level->id }}">
                            <label class="form-check-label">{{ $level->level_name }}</label>
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
        <form method="POST" id="editForm" class="modal-content">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Exam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-12">
                    <label>Exam Name</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Term</label>
                    <select name="term_id" id="edit-term" class="form-select" required>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Subjects</label>
                    <div class="border p-2 rounded" style="max-height:200px;overflow:auto;" id="edit-subject-checkboxes">
                        @foreach($subjects as $subject)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" id="edit-subject{{ $subject->id }}">
                            <label class="form-check-label" for="edit-subject{{ $subject->id }}">{{ $subject->subject_name }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Classes</label>
                    <div class="border p-2 rounded" style="max-height:200px;overflow:auto;" id="edit-class-checkboxes">
                        @foreach($levels as $level)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="class_ids[]" value="{{ $level->id }}" id="edit-class{{ $level->id }}">
                            <label class="form-check-label" for="edit-class{{ $level->id }}">{{ $level->level_name }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" id="edit-status" value="1"> Active
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Analysed</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_analysed" id="edit-analysed" value="1"> Analysed
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            let id = this.dataset.id;
            let name = this.dataset.name;
            let term_id = this.dataset.term_id;
            let status = this.dataset.status;
            let analysed = this.dataset.is_analysed;
            let subjects = JSON.parse(this.dataset.subjects);
            let classes = JSON.parse(this.dataset.classes);

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-term').value = term_id;
            document.getElementById('edit-status').checked = status == 1;
            document.getElementById('edit-analysed').checked = analysed == 1;

            document.querySelectorAll('#edit-subject-checkboxes input').forEach(cb => {
                cb.checked = subjects.includes(parseInt(cb.value));
            });
            document.querySelectorAll('#edit-class-checkboxes input').forEach(cb => {
                cb.checked = classes.includes(parseInt(cb.value));
            });

            document.getElementById('editForm').action = `/exams/${id}`;
        });
    });
});
</script>
@endsection
