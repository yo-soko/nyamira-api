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
                    <a href="javascript:void(0);" onclick="printReport()" data-bs-toggle="tooltip" title="Print">
                        <i class="ti ti-printer"></i>
                    </a>
                </li>
                <li class="me-2">
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
                </li>
            </ul>
        </div>
        <div id="print-area"> 
            <div class="print-header d-none d-print-block mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <img src="{{ asset('build/img/school-logo.png') }}" alt="School Logo" style="height: 70px;">
                    </div>
                    <div class="text-center flex-fill">
                        <h4>MINISTRY OF EDUCATION</h4>
                        <h5>STATE DEPARTMENT OF MIDDLE LEARNING AND BASIC EDUCATION</h5>
                        <h6>ASSESSMENT OUTCOMES REPORT</h6>
                    </div>
                    <div class="text-end">
                        <img src="{{ asset('build/img/kenya-logo.png') }}" alt="Kenya Logo" style="height: 70px;">
                    </div>
                </div>
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
            <div class="print-footer text-center">
                <small><strong>Software developed by JavaPA LTD 0727147442</strong></small>
            </div>

        </div>

    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>
<script>
    function printReport() {
        const printContents = document.getElementById('print-area').innerHTML;
        const printWindow = window.open('', '', 'height=900,width=1200');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Assessment Report</title>
                    <link rel="stylesheet" href="{{ asset('build/css/bootstrap.min.css') }}">
                    <style>
                        .d-print-block {
                           display: block !important;
                        }

                        @media print {
                            .d-none {
                                display: none !important;
                            }

                            .d-print-block {
                                display: block !important;
                            }

                            .print-header h4, .print-header h5, .print-header h6 {
                                margin: 2px 0;
                                text-align: center;
                            }

                            .print-header img {
                                height: 70px;
                                width: auto;
                            }

                            .print-header {
                                margin-bottom: 20px;
                            }
                            .page-break {
                                page-break-after: always;
                            }
                            .print-footer {
                                position: fixed;
                                bottom: 0;
                                left: 0;
                                right: 0;
                                text-align: center;
                                font-size: 12pt;
                                padding: 5px 0;
                                border-top: 1px solid #000;
                                background: white;
                            }
                        }

                        body {
                            font-family: Arial, sans-serif;
                            font-size: 13pt;
                            color: black;
                            padding: 20px;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 1rem;
                        }

                        th, td {
                            border: 1px solid #000;
                            padding: 5px;
                            text-align: center;
                        }

                        th {
                            background: #f0f0f0;
                        }

                        .table-sm th, .table-sm td {
                            padding: 3px;
                        }

                        .row {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 1rem;
                        }

                        .col-md-6 {
                            width: 48%;
                        }

                        h4, h6 {
                            text-align: center;
                        }
                    </style>
                </head>
                <body onload="window.print(); setTimeout(() => window.close(), 500);">
                    ${printContents}
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>

@endsection
