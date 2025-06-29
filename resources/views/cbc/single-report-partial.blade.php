@php
    $student = $student ?? null;
    $marks = $marks ?? collect();
    $summary = $summary ?? [];
    $facilitatorName = $facilitatorName ?? '';
    $rubricCode = $rubricCode ?? fn($x) => '-';
    $chartData = $chartData ?? collect();
    $classAverage = $classAverage ?? 0;
    $levelAverage = $levelAverage ?? 0;

    
@endphp

        <div class="container-report bg-white p-4 page-break"> 
           <div class="d-flex align-items-center justify-content-between mb-2" style="flex-wrap: nowrap;">
         
                <div style="flex: 0 0 120px;">
                    <img src="{{ asset('build/img/school-logo.png') }}" alt="School Logo" class="logo-img" style="height: 70px; width: auto;">
                </div>

                <div style="flex: 1; text-align: center;">
                    <h4 class="mb-1">MINISTRY OF EDUCATION</h4>
                    <h5 class="mb-1">STATE DEPARTMENT OF MIDDLE LEARNING AND BASIC EDUCATION</h5>
                    <h6 class="mb-0">SCHOOL TERM SUMMATIVE REPORT FOR EARLY YEARS EDUCATION</h6>
                </div>

                <div style="flex: 0 0 120px; text-align: right;">
                    <img src="{{ asset('build/img/kenya-logo.png') }}" alt="Kenya Logo" class="logo-img" style="height: 70px; width: auto;">
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
                <canvas id="performanceChart_{{ $student->id }}" height="150"></canvas>

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
<script src="{{ asset('build/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('build/plugins/chartjs/chart.min.js') }}"></script>
<script>
    (function() {
        const ctx = document.getElementById('performanceChart_{{ $student->id }}')?.getContext('2d');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('subject')) !!},
                datasets: [
                    {
                        label: 'Student Score',
                        data: {!! json_encode($chartData->pluck('score')) !!},
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
    })();
</script>
