@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Assesment Outcomes @if(!empty($filters))  @endif</h4>
                    <h6>
                        @if(!empty($filters['level_id']))
                            Grade: {{ $filters['level_name'] ?? '-' }}
                        @endif
                        @if(!empty($filters['class_id']))
                            | Stream: {{ $filters['class_name'] ?? '-' }}
                        @endif
                        @if(!empty($filters['exam_id']))
                            | Assesment: {{ $filters['name'] ?? '-' }}
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
                        if ($score >= 80) return 'E.E';
                        if ($score >= 60) return 'M.E';
                        if ($score >= 40) return 'A.E';
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
                                <th>Grade</th>
                                <th>Stream</th>
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

                                            // Kiswahili translation logic
                                            $isSwahili = strtolower($subject->subject_name) === 'kiswahili';
                                            $swahiliMap = [
                                                'Exceeding Expectation' => 'KUZ',
                                                'Meeting Expectation' => 'KUF',
                                                'Approaching Expectation' => 'KUK',
                                                'Below Expectation' => 'MM',
                                            ];

                                            $defaultMap = [
                                                'Exceeding Expectation' => 'E.E',
                                                'Meeting Expectation' => 'M.E',
                                                'Approaching Expectation' => 'A.E',
                                                'Below Expectation' => 'B.E',
                                            ];

                                            $gradeLabel = '-';
                                            if ($result && !empty($result->grade)) {
                                                $gradeLabel = ($isSwahili ? $swahiliMap[$result->grade] ?? $result->grade : $defaultMap[$result->grade] ?? $result->grade);
                                            } elseif ($result && is_null($result->marks)) {
                                                $gradeLabel = 'Absent';
                                            }
                                        @endphp
                                        <td>{{ $gradeLabel }}</td>
                                    @endforeach


                                    @if($subjects->count() >= 2)
                                        <td>{{ $averageGrade }}</td>
                                    @endif
                                   

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-3">
                        <h6><strong>Grade Key:</strong></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr><th colspan="2" class="text-center">English Subjects</th></tr>
                                        <tr>
                                            <th>Initial</th>
                                            <th>Meaning</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>E.E</td><td>Exceeding Expectation</td></tr>
                                        <tr><td>M.E</td><td>Meeting Expectation</td></tr>
                                        <tr><td>A.E</td><td>Approaching Expectation</td></tr>
                                        <tr><td>B.E</td><td>Below Expectation</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr><th colspan="2" class="text-center">Kiswahili</th></tr>
                                        <tr>
                                            <th>Kifupi</th>
                                            <th>Maana</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>KUZ</td><td>Kuzidi Matarajio</td></tr>
                                        <tr><td>KUF</td><td>Kufikia Matarajio</td></tr>
                                        <tr><td>KUK</td><td>Kukaribia Matarajio</td></tr>
                                        <tr><td>MM</td><td>Mbali na Matarajio</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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
