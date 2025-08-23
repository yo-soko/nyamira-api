<?php $page = 'dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')
@php
    $hour = date('H');
    if ($hour < 12) {
        $greeting = 'Good Morning';
    } elseif ($hour < 18) {
        $greeting = 'Good Afternoon';
    } else {
        $greeting = 'Good Evening';
    }
@endphp
<div class="page-wrapper">
    <div class="content">
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>

                <h2 class="mb-1">
                <img src="{{URL::asset('build/img/icons/hand01.svg')}}" class="hand-img" alt="img">
                    {{ $greeting }} <span class="text-primary fw-bold"> {{ auth()->user()->name }}</span>
                 </h2>
                <p>Its nice  to see you <span class="text-primary fw-bold">Today</span></p>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="input-icon-start position-relative">
                        <span class="input-icon-addon fs-16 text-gray-9">
                            <i class="ti ti-calendar"></i>
                        </span>
                        <input type="text" class="form-control date-range bookingrange" placeholder="Search Product">
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse" class=""><i data-feather="chevron-up" class="feather-16"></i></a>
                </li>
            </ul>
        </div>
        <!-- Welcome Wrap -->
    <div class="welcome-wrap mb-4">
        <div class=" d-flex align-items-center justify-content-between flex-wrap">
            <div class="mb-3">
                <h2 class="mb-1 text-white">Welcome Back,  {{ auth()->user()->name }}</h2>
            </div>
            <div class="d-flex align-items-center flex-wrap mb-1">
                <a href="{{url('profile')}}" class="btn btn-dark btn-md me-2 mb-2">Profile</a>

                <a href="{{url('general-settings')}}" class="btn btn-light btn-md mb-2">Settings</a>
            </div>
        </div>
        <div class="welcome-bg">
            <img src="{{URL::asset('build/img/bg/welcome-bg-02.svg')}}" alt="img" class="welcome-bg-01">
            <img src="{{URL::asset('build/img/bg/welcome-bg-01.svg')}}" alt="img" class="welcome-bg-03">
        </div>
    </div>
    <!-- /Welcome Wrap -->

    <div class="row">
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill" style="background-color: #6f42c1;"> <!-- light yellow -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between"></div>
                    <div class="d-flex align-items-center justify-content-between">
                    <div>
                        @if($balance > 0)
                            <h2 class="mb-1 text-danger">Ksh {{ number_format($balance, 2) }}</h2>
                            <p class="fs-13 text-dark">Outstanding Balance</p>
                        @elseif($balance == 0)
                            <h2 class="mb-1 text-success">No Balance</h2>
                            <p class="fs-13 text-dark">You have cleared your fees</p>
                        @else
                            <h2 class="mb-1 text-teal">Ksh {{ number_format($balance, 2) }}</h2>
                            <p class="fs-13 text-dark">Over Paid</p>
                        @endif
                    </div>


                        <div>
                            <a href="{{ route('student.fee-payments') }}" class="btn btn-light btn-sm mt-2">
                                View Payment History
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-secondary-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">

                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $summary['total_subjects'] ?? '-'}}</h2>
                                <p class="fs-13">Total Subjects</p>
                            </div>
                            <div class="company-bar1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-secondary-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">

                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $summary['term_name'] ?? '-' }}</h2>
                                <p class="fs-13">Term</p>
                            </div>
                            <div class="company-bar1"></div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-secondary-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">

                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1"> {{ $student->class->level->level_name ?? '' }} - {{ $student->class->stream->name ?? '' }}</h2>
                                <p class="fs-13">Class</p>
                            </div>
                            <div class="company-bar1"></div>
                        </div>
                    </div>
                </div>
            </div>
                    <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill bg-secondary-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">

                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">{{ $summary['grade'] ?? '-'}}</h2>
                                <p class="fs-13">Grade</p>
                            </div>
                            <div class="company-bar1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row mt-4">
    <!-- Chart Column -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5>Performance Per Subject</h5>
            </div>
            <div class="card-body" style="position: relative; height: 300px;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Column -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5>Marks Summary</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Subject</th>
                                <th>Grade</th>
                                <th>Comment</th>
                                <th>Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($marks as $mark)
                                <tr>
                                    <td>{{ $mark->subject->subject_name ?? '-' }}</td>
                                    <td>
                                        @php
                                            $subject = strtolower($mark->subject->subject_name ?? '');
                                            $fullGrade = strtolower($mark->grade ?? '');
                                            $gradeCode = '-';

                                            // Convert full grade names to codes
                                            if (str_contains($fullGrade, 'exceeding expectation')) $gradeCode = 'E.E';
                                            elseif (str_contains($fullGrade, 'meeting expectation')) $gradeCode = 'M.E';
                                            elseif (str_contains($fullGrade, 'approaching expectation')) $gradeCode = 'A.E';
                                            elseif (str_contains($fullGrade, 'below expectation')) $gradeCode = 'B.E';

                                            // For Kiswahili, use special subject performance codes
                                            if ($subject === 'kiswahili') {
                                                if (str_contains($fullGrade, 'exceeding expectation')) $gradeCode = 'KUZ';
                                                elseif (str_contains($fullGrade, 'meeting expectation')) $gradeCode = 'KUF';
                                                elseif (str_contains($fullGrade, 'approaching expectation')) $gradeCode = 'KUK';
                                                elseif (str_contains($fullGrade, 'below expectation')) $gradeCode = 'MM';
                                            }
                                        @endphp
                                        {{ $gradeCode }}
                                    </td>

                                    <td>{{ $mark->comments ?? '-' }}</td>
                                    <td>{!! $mark->absent ? '<span class="text-danger">✔</span>' : '<span class="text-success">✖</span>' !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No marks available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card border">
            <div class="card-header">
                <h6 class="mb-0">Performance Key</h6>
            </div>
          <div class="card-body p-3">
    <div class="row">
        <!-- General Grade Codes -->
        <div class="col-md-6">
            <p class="mb-1"><strong>General Grade Codes:</strong></p>
            <ul class="mb-2">
                <li><strong>E.E</strong> – Exceeding Expectations</li>
                <li><strong>M.E</strong> – Meeting Expectations</li>
                <li><strong>A.E</strong> – Approaching Expectations</li>
                <li><strong>B.E</strong> – Below Expectations</li>
            </ul>
        </div>

        <!-- Kiswahili Subject Codes -->
        <div class="col-md-6">
            <p class="mb-1"><strong>Kiswahili Subject Codes:</strong></p>
            <ul class="mb-2">
                <li><strong>KUZ</strong> – Kuzidi Matarajio</li>
                <li><strong>KUF</strong> – Kufikia Matarajio</li>
                <li><strong>KUK</strong> – Kukaribia Matarajio</li>
                <li><strong>MM</strong> – Mbali na Matarajio</li>
            </ul>
        </div>
    </div>
</div>

        </div>
    </div>
</div>


    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
        <p class="mb-0 text-gray-9">&copy; JavaPA. All Right Reserved</p>
        <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">JavaPA</a></p>
    </div>
</div>

<script src="{{ asset('build/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('build/plugins/chartjs/chart.min.js') }}"></script>

<script>
(function() {
    const ctx = document.getElementById('performanceChart')?.getContext('2d');
    if (!ctx) return;

    const chartData = @json($chartData);

    // Map grades to numeric positions for the chart
    const gradeToPosition = score => {
        if (score >= 80) return 4; // E.E
        if (score >= 60) return 3; // M.E
        if (score >= 40) return 2; // A.E
        if (score > 0)  return 1; // B.E
        return 0;
    };

    const positionToGrade = [' ', 'B.E', 'A.E', 'M.E', 'E.E'];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(item => item.subject),
            datasets: [
                {
                    label: '{{ $summary["exam_name"] ?? "Latest Exam" }}',
                    data: chartData.map(item => gradeToPosition(item.score)),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    min: 0,
                    max: 4,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return positionToGrade[value];
                        }
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
                        label: function(context) {
                            const score = chartData[context.dataIndex].score;
                            let grade = '-';
                            if (score >= 80) grade = 'E.E';
                            else if (score >= 60) grade = 'M.E';
                            else if (score >= 40) grade = 'A.E';
                            else if (score > 0) grade = 'B.E';
                            return `${context.dataset.label}: ${grade}`;
                        }
                    }
                }
            }
        }
    });
})();
</script>



@endsection
