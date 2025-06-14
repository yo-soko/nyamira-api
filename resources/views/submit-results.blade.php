<?php $page = 'holidays'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')   
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Manage Exams</h4>
                    <h6>Manage your examinations</h6>
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
            @can('add exams')
            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-holiday"><i class="ti ti-circle-plus me-1"></i>Add Exams</a>
            </div>
            @endcan
        </div>
      <div class="container">
    <h3>Filter Exam to Submit Results</h3>

    <form action="{{ route('results.entry') }}" method="GET">
        @csrf
        <div class="mb-3">
            <label for="term_id" class="form-label">Select Term</label>
            <select name="term_id" id="term_id" class="form-control" required>
                <option value="">-- Choose Term --</option>
                @foreach($terms as $term)
                    <option value="{{ $term->id }}">{{ $term->term_name }} - {{ $term->year }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="class_id" class="form-label">Select Class</label>
            <select name="class_id" id="class_id" class="form-control" required>
                <option value="">-- Choose Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">  {{ $class->classLevel->name ?? 'No Level' }} - {{ $class->stream->name ?? 'No Stream' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="subject_id" class="form-label">Select Subject</label>
            <select name="subject_id" id="subject_id" class="form-control" required>
                <option value="">-- Choose Subject --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="exam_id" class="form-label">Select Exam</label>
            <select name="exam_id" id="exam_id" class="form-control" required>
                <option value="">-- Choose Exam --</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Proceed</button>
    </form>
</div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const holiday_name = this.dataset.holiday_name;
                const from_date = this.dataset.from_date;
                const to_date = this.dataset.to_date;
                const days_count = this.dataset.days_count;
                const description = this.dataset.description;
                const status = this.dataset.status;
        
                document.getElementById('edit-id').value = id;
                document.getElementById('holiday_name').value = holiday_name;
                document.getElementById('from_date').value = from_date;
                document.getElementById('to_date').value = to_date;
                document.getElementById('days_count').value = days_count;
                $('#summernote2').summernote('code', description);
                document.getElementById('edit-status').checked = status == 1;

                // Set form action dynamically
                document.getElementById('editForm').action = `/holiday/${id}`;
            });
        });
    });
document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.dataset.id;
                        document.getElementById('delete-id').value = id;
                    });
                });
            });
</script>
@endsection
