@extends('layout.mainlayout')
@section('content')
@include('layout.toast') 
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 3mm;
        }

        body {
            margin: 0 !important;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 15pt;
            background: white;
            color: black;
        }

        body * {
            visibility: hidden;
        }

        .container-report, .container-report * {
            visibility: visible;
        }

        .container-report {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            min-height: 100%;
            background: white;
            box-sizing: border-box;
        }

        .page-header,
        .footer,
        .d-print-none {
            display: none !important;
            
        }

        h4, h5, h6 {
            font-size: 16pt;
            margin: 4px 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse !important;
        }

        th, td {
            padding: 6px;
            border: 1px solid #000 !important;
            text-align: left;
            vertical-align: middle;
            font-size: 15pt;
        }

        .table th {
            background: #f0f0f0;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .col-6, .col-md-6 {
            width: 50%;
        }


        canvas {
            display: block;
            margin: 0 auto;
            page-break-inside: avoid;
            image-rendering: crisp-edges; /* Enhance rendering */
            image-rendering: pixelated;   /* For pixel-based sharpness */
            -webkit-print-color-adjust: exact; /* Ensures full color in Chrome */
            print-color-adjust: exact;
        }

        ul {
            padding-left: 1rem;
        }

        ul li {
            margin-bottom: 2px;
        }
        .logo-img {
            height: 200px;
            width: 175px;
            /* max-height: 100px; */
        }
        
    }
</style>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
              
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
        <div class="mb-4 d-print-none">
    <form method="GET" action="{{ route('cbc.report') }}" class="form-inline">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="term_id" class="form-label mb-0">Select Term:</label>
                <select name="term_id" id="term_id" class="form-select">
                    @foreach($terms as $term)
                        <option value="{{ $term->id }}" {{ $termId == $term->id ? 'selected' : '' }}>
                            {{ $term->term_name }} - {{ \Carbon\Carbon::parse($term->start_date)->format('M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
</div>

        <div class="container-report bg-white p-4"> 
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="text-start">
                    <img src="{{ asset('build/img/school-logo.png') }}" alt="School Logo" class="logo-img" style="height: 70px;">
                </div>
                <div class="text-center w-100">
                    <h4>MINISTRY OF EDUCATION</h4>
                    <h5>STATE DEPARTMENT OF MIDDLE LEARNING AND BASIC EDUCATION</h5>
                    <h6>SCHOOL TERM SUMMATIVE REPORT FOR EARLY YEARS EDUCATION</h6>
                </div>
                <div class="text-end">
                    <img src="{{ asset('build/img/kenya-logo.png') }}" alt="Kenya Logo" class="logo-img" style="height: 70px;">
                </div>
            </div>

           
            <div class="table-responsive">
                <table class="table table-bordered small">
                
                        <tr>
                            <td>Name of Learner:</td><td>{{ $student->first_name ?? '' }} {{ $student->second_name ?? '' }} {{ $student->last_name ?? '' }}</td>
                            <td>Grade:</td><td>{{ $student->class->level->level_name ?? '' }} {{ $student->class->stream->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>School Name:</td><td>{{ $student->school ?? 'JEMMAPP PREPARATORY SCHOOL' }}</td>
                            <td>Term:</td><td>{{ $summary['term_name']}}</td>
                        </tr>
                        <tr>
                            <td>UPI No.:</td><td>{{ $student->upi ?? ' ' }}</td>
                            <td>Year:</td><td>{{ date('Y') }}</td>
                        </tr>
                        <tr>
                            <td>Facilitator’s Name:</td><td colspan="3">{{ $facilitatorName ?? ' '}}</td>
                        </tr>
                   
                </table>
            </div>
            </br>
            <div class="table-responsive">

                <table class="table table-bordered text-center align-middle small">
                    <thead>
                        <tr>
                            <th rowspan="2">Learning Area</th>
                            <th colspan="2">Assessment 1</th>
                            <th colspan="2">Assessment 2</th>
                            <th colspan="2">Assessment 3</th>
                        </tr>
                        <tr>
                            <th></th><th>Comment</th>
                            <th></th><th>Comment</th>
                            <th></th><th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($marks as $mark)
                        <tr>
                            <td class="text-start">{{ $mark->subject->subject_name ?? 'Unknown' }}</td>
                            @for ($i = 1; $i <= 3; $i++)
                                <td>{{ isset($mark->{"assessment_$i"}) ? $rubricCode($mark->{"assessment_$i"}) : '-' }}</td>
                                <td>{{ isset($mark->{"comment_$i"}) ? $mark->{"comment_$i"} : '-' }}</td>
                            @endfor
                        </tr>
                    @endforeach

                    <tr>
                        <td><strong>Average Score</strong></td>
                        @for ($i = 1; $i <= 3; $i++)
                            @php $avg = $summary["avg_$i"] ?? null; @endphp
                            <td><strong>{{ $rubricCode($avg) }}</strong></td>
                            <td></td>
                        @endfor

                    </tr>

                    </tbody>

                </table>
            </div>
           
            <div class="my-4">
                <canvas id="performanceChart" height="150"></canvas>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <p><strong>Class Teacher’s Comments:</strong> _________________________________________</p>
                    <p><strong>Signature:</strong> _____________ Date: __________</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Head Teacher’s Comments:</strong> _________________________________________</p>
                    <p><strong>Signature:</strong> _____________ Date: __________</p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Parent/Guardian Signature:</strong> _____________ Date: __________</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Term ends on:</strong> {{ \Carbon\Carbon::parse($summary['term_end_date'])->format('d M Y') ?? '-' }} <strong>Next term begins on:</strong> ____________</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">
                    <strong>Rubrics Key:</strong>
                    <ul class="list-unstyled mb-0 small">
                        <li><strong>4 – E.E</strong>: Exceeding Expectation</li>
                        <li><strong>3 – M.E</strong>: Meeting Expectation</li>
                    </ul>
                </div>
                <div class="col-6">
                    <strong>&nbsp;</strong> {{-- Align with left column --}}
                    <ul class="list-unstyled small">
                        <li><strong>2 – A.E</strong>: Approaching Expectation</li>
                        <li><strong>1 – B.E</strong>: Below Expectation</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 d-print-none">
            <button class="btn btn-primary" onclick="window.print()">Print Report</button>
        </div>

    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div> 
<script src="{{ asset('build/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('build/plugins/chartjs/chart.min.js') }}"></script>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData->pluck('subject')) !!}, // subject names
            datasets: [
                {
                    label: 'Student Score',
                    data: {!! json_encode($chartData->pluck('score')) !!}, // student marks
                    backgroundColor: 'rgba(54, 162, 235, 0.4)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Class Avg',
                    data: new Array({{ $chartData->count() }}).fill({{ round($classAverage ?? 0, 2) }}),
                    borderColor: 'rgb(17, 238, 28)',
                    borderWidth: 2,
                    borderDash: [10, 5],
                    pointRadius: 0,
                    fill: false
                },
                {
                    label: 'Level Avg',
                    data: new Array({{ $chartData->count() }}).fill({{ round($levelAverage ?? 0, 2) }}),
                    borderColor: 'rgb(243, 123, 10)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        callback: function (value) {
                            if (value == 100) return ' ';
                            if (value >= 80) return 'E.E';
                            if (value >= 60) return 'M.E';
                            if (value >= 40) return 'A.E';
                            if (value > 0) return 'B.E';
                            return ' ';
                        },
                        stepSize: 20
                    },
                    title: {
                        display: true,
                        text: 'Grade'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Subjects'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let score = context.parsed.y;
                            let label = context.dataset.label;
                            let grade = '-';
                            if (score >= 80) grade = 'E.E';
                            else if (score >= 60) grade = 'M.E';
                            else if (score >= 40) grade = 'A.E';
                            else if (score > 0) grade = 'B.E';

                            return `${label}: ${score} (${grade})`;
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
