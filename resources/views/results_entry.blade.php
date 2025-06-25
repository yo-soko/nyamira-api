<?php $page = 'shift'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Submit Assesments</h4>
                    <h6>Ensure to submit correct assesment. Those learners who were never assessed to be marked absent</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
                <li class="me-2">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
                </li>
            </ul>
         
        </div>
        <!-- /product list -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Select Status
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Active</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Inactive</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Sort By : Last 7 Days
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recently Added</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Ascending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
          
            <div class="card-body p-0">
                <div class="table-responsive">
                    <form method="POST" action="{{ route('results.store') }}">
                    @csrf
                    <input type="hidden" name="term_id" value="{{ $termId }}">
                    <input type="hidden" name="exam_id" value="{{ $examId }}">
                    <input type="hidden" name="class_id" value="{{ $classId }}">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Learner Name</th>
                                <th>Stream</th>
                                <th>Learning Area</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Comments</th>
                                <th>Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                    <input type="hidden" name="exams[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                </td>
                                <td>{{ $student->class->level->level_name }} {{ $student->class->stream->initials }}</td>

                                <td>
                                    {{ \App\Models\Subject::find($subjectId)->subject_name }}
                                    <input type="hidden" name="exams[{{ $student->id }}][subject]" value="{{ $subjectId }}">
                                </td>

                                <td>
                                    <input type="number" style="min-width: 80px;" min="0" max="100" name="exams[{{ $student->id }}][marks]" class="form-control">
                                </td>

                                <td>
                                    <select name="exams[{{ $student->id }}][grade]" class="form-control" style="min-width: 80px;">
                                            <option value="">select here</option>
                                            @php
                                                $subject = \App\Models\Subject::find($subjectId)->subject_name;
                                                $isSwahili = strtolower($subject) == 'kiswahili';
                                                $grades = [
                                                    'Exceeding Expectation' => $isSwahili ? 'KUZ' : 'EE',
                                                    'Meeting Expectation' => $isSwahili ? 'KUF' : 'ME',
                                                    'Approaching Expectation' => $isSwahili ? 'KUK' : 'AE',
                                                    'Below Expectation' => $isSwahili ? 'MM' : 'BE',
                                                ];
                                            @endphp

                                            @foreach ($grades as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="exams[{{ $student->id }}][comments]" class="form-control" placeholder="Comments (optional)">
                                </td>
                                <td>
                                    <label>
                                        <input type="checkbox" name="exams[{{ $student->id }}][absent]" value="1"> Absent
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success mt-3">Submit Results</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /product list -->
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div> 

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Grade auto-selection when marks are typed
    document.querySelectorAll('input[name^="exams"][name$="[marks]"]').forEach(function (input) {
        input.addEventListener('input', function () {
            let marks = parseInt(this.value);
            let gradeSelect = this.closest('tr').querySelector('select[name$="[grade]"]');
            if (isNaN(marks)) return;

            let grade = '';
            if (marks >= 0 && marks <= 39) grade = 'Below Expectation';
            else if (marks >= 40 && marks <= 59) grade = 'Approaching Expectation';
            else if (marks >= 60 && marks <= 79) grade = 'Meeting Expectation';
            else if (marks >= 80 && marks <= 100) grade = 'Exceeding Expectation';

            // Set the grade option based on value (not label)
            for (let i = 0; i < gradeSelect.options.length; i++) {
                if (gradeSelect.options[i].value === grade) {
                    gradeSelect.selectedIndex = i;
                    break;
                }
            }
        });
    });

    // Disable marks & grade if "Absent" is checked
    document.querySelectorAll('input[type=checkbox][name$="[absent]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const row = this.closest('tr');
            const marksInput = row.querySelector('input[name$="[marks]"]');
            const gradeSelect = row.querySelector('select[name$="[grade]"]');

            if (this.checked) {
                marksInput.value = '';
                marksInput.disabled = true;
                gradeSelect.selectedIndex = 0;
                gradeSelect.disabled = true;
            } else {
                marksInput.disabled = false;
                gradeSelect.disabled = false;
            }
        });
    });
});
</script>



@endsection
