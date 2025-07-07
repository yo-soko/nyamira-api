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
                                    @foreach($exam->examSubjectsClasses->pluck('school_class_id')->unique() as $classId)
                                        @php
                                            $class = \App\Models\SchoolClass::with('level','stream')->find($classId);
                                        @endphp
                                        @if($class)
                                            <span class="badge bg-success">
                                                {{ $class->level->level_name ?? '' }} {{ $class->stream->name ?? '' }}
                                            </span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-{{ $exam->status ? 'success' : 'danger' }}">
                                        {{ $exam->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $exam->is_analysed ? 'primary' : 'warning' }}">
                                        {{ $exam->is_analysed ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn"
                                        data-id="{{ $exam->id }}"
                                        data-name="{{ $exam->name }}"
                                        data-term_id="{{ $exam->term_id }}"
                                        data-status="{{ $exam->status }}"
                                        data-is_analysed="{{ $exam->is_analysed }}"
                                        data-subjects="{{ json_encode($exam->examSubjectsClasses->pluck('subject_id')->unique()) }}"
                                        data-classes="{{ json_encode($exam->examSubjectsClasses->pluck('school_class_id')->unique()) }}"
                                        data-bs-toggle="modal" data-bs-target="#edit-exam">
                                        Edit
                                    </button>
                                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
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
                    <div class="border p-2 rounded" id="add-subject-checkboxes" style="max-height:200px;overflow:auto;">
                        <div class="text-muted">Select classes to load subjects</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Classes</label>
                    <div class="border p-2 rounded" id="add-class-checkboxes" style="max-height:200px;overflow:auto;">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classCheckboxes = document.querySelectorAll('#add-class-checkboxes input[name="class_ids[]"]');
    const subjectContainer = document.getElementById('add-subject-checkboxes');

    classCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSubjects);
    });

    function updateSubjects() {
        let checkedClasses = Array.from(classCheckboxes).filter(cb => cb.checked).map(cb => cb.value);

        if (checkedClasses.length === 0) {
            subjectContainer.innerHTML = "<div class='text-muted'>Select classes to load subjects</div>";
            return;
        }

        fetch(`/classes/${checkedClasses.join(',')}/subjects`)
            .then(res => res.json())
            .then(data => {
                let uniqueSubjects = {};
                data.forEach(subject => {
                    uniqueSubjects[subject.id] = subject.subject_name;
                });

                let html = "";
                for (const [id, name] of Object.entries(uniqueSubjects)) {
                    html += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="subject_ids[]" value="${id}" checked>
                            <label class="form-check-label">${name}</label>
                        </div>
                    `;
                }
                subjectContainer.innerHTML = html;
            });
    }
});
</script>
@endsection
