@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Exam Results @if(!empty($filters)) - Filters Applied @endif</h4>
                    <h6>
                        @if(!empty($filters['level_id']))
                            Level: {{ $filters['level_name'] ?? '-' }}
                        @endif
                        @if(!empty($filters['class_id']))
                            | Class: {{ $filters['class_name'] ?? '-' }}
                        @endif
                        @if(!empty($filters['exam_id']))
                            | Exam: {{ $filters['name'] ?? '-' }}
                        @endif
                        @if(!empty($filters['term_id']))
                            | Term: {{ $filters['term_name'] ?? '-' }}
                        @endif
                    </h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li class="me-2">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}" alt="img"></a>
                </li>
                <li class="me-2">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    @php
                    function scoreToGrade($score) {
                        if ($score >= 75) return 'E.E';
                        if ($score >= 50) return 'M.E';
                        if ($score >= 25) return 'A.E';
                        if ($score >= 0) return 'B.E';
                        return '-';
                    }
                    @endphp
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Learner Name</th>
                                <th>Adm No</th>
                                <th>Level</th>
                                <th>Class</th>
                                @foreach($subjects as $subject)
                                    <th>{{ $subject->subject_name }}</th>
                                @endforeach
                                @if($subjects->count() >= 2)
                                    <th>Average Grade</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsWithAverage as $studentData)
                                @php
                                    $student = $studentData['student'];
                                    $resultsForStudent = $studentData['results'];
                                    $averageGrade = scoreToGrade($studentData['average_score']);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ $student->student_reg_number }}</td>
                                    <td>{{ $student->class->level->level_name ?? '-' }}</td>
                                    <td>{{ $student->class->stream->name ?? '-' }}</td>

                                    @foreach($subjects as $subject)
                                        @php
                                            $result = $resultsForStudent->firstWhere('subject.id', $subject->id);
                                        @endphp
                                        <td>
                                            @if($result)
                                                @if(!is_null($result->marks))
                                                    {{ $result->grade }}
                                                @elseif(!empty($result->grade))
                                                    {{ $result->grade }}
                                                @else
                                                    Absent
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach

                                    @if($subjects->count() >= 2)
                                        <td>{{ $averageGrade }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

@endsection
