<?php $page = 'fee-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">

        <form method="GET" action="{{ route('fee.dashboard') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label>Academic Year</label>
                <select name="year" class="form-control">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Term</label>
                <select name="term_id" class="form-control">
                    <option value="">All Terms</option>
                    @foreach($terms as $term)
                        <option value="{{ $term->id }}" {{ request('term_id') == $term->id ? 'selected' : '' }}>
                            {{ $term->term_name }} - {{ $term->year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>




        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Fee Management Dashboard</h4>
                <p class="text-muted">Analytics of JEMMAPP fees and payments</p>
            </div>
        </div>

        <div class="mb-3 d-flex gap-2">

            <a href="{{ route('fee.dashboard.download', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-outline-primary">
                <i class="ti ti-file-text me-1"></i> Download PDF
            </a>

        </div>

        <div class="row">
            <!-- Tuition Summary -->
            <div class="col-md-4">
                <div class="card shadow bg-secondary text-white">
                    <div class="card-body">
                        <h6>Tuition Paid ({{ $currentTerm->term_name ?? 'Current Term' }})</h6>
                        <h3>Ksh {{ number_format($actualPaidTuition, 2) }}</h3>
                        <p>Out of Ksh {{ number_format($expectedTuition, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Meals Summary -->
            <div class="col-md-4">
                <div class="card shadow bg-info text-white">
                    <div class="card-body">
                        <h6>Meals Paid</h6>
                        <h3>Ksh {{ number_format($actualPaidMeal, 2) }}</h3>
                        <p>Out of Ksh {{ number_format($expectedMeal, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Transport Summary -->
            <div class="col-md-4">
                <div class="card shadow bg-teal text-white">
                    <div class="card-body">
                        <h6>Transport Paid</h6>
                        <h3>Ksh {{ number_format($actualPaidTransport, 2) }}</h3>
                        <p>Out of Ksh {{ number_format($expectedTransport, 2) }}</p>
                    </div>
                </div>
            </div>


            <!-- General Overview -->
            <div class="col-md-6 col-lg-3">
                <div class="card shadow bg-danger text-white">
                    <div class="card-body">
                        <h6>Total Outstanding</h6>
                        <h3>Ksh {{ number_format($outstandingBalance, 2) }}</h3>
                        <p>Total Collected: Ksh {{ number_format($totalCollected, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Overview Chart -->
        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header"><h6>Payments by Level</h6></div>
                    <div class="card-body">
                        <canvas id="levelPaymentChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5>Total Students</h5>
                        <h2>{{ $totalStudents }}</h2>
                        <p class="text-muted">Across all classes & terms</p>
                        <a href="{{ route('fee-payments.index') }}" class="btn btn-outline-primary btn-sm">View All Payments</a>
                    </div>
                </div>

                <div class="card shadow mt-3">
                    <div class="card-body text-center">
                        <h6>Export Dashboard</h6>
                        <a href="#" class="btn btn-sm btn-outline-secondary">Download PDF</a>
                        <!-- <a href="#" class="btn btn-sm btn-outline-secondary">Print</a> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Recent Payments Summary (Can be shown later if needed) -->
        {{--
        <div class="card mt-4">
            <div class="card-header"><h6>Recent Payments</h6></div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($recentPayments as $payment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $payment->student->first_name }} {{ $payment->student->last_name }} -
                            Ksh {{ number_format($payment->amount_paid, 2) }} ({{ $payment->payment_mode }})
                            <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        --}}
    </div>
</div>

<!-- Chart Script -->
<script src="{{ URL::asset('build/plugins/chartjs/chart.min.js') }}"></script>
<script>
    const ctx = document.getElementById('levelPaymentChart').getContext('2d');
    const levelChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($levelData, 'name')) !!},
            datasets: [
                {
                    label: 'Expected',
                    backgroundColor: '#6f42c1',
                    data: {!! json_encode(array_column($levelData, 'expected')) !!}
                },
                {
                    label: 'Paid',
                    backgroundColor: '#198754',
                    data: {!! json_encode(array_column($levelData, 'paid')) !!}
                },
                {
                    label: 'Balance',
                    backgroundColor: '#dc3545',
                    data: {!! json_encode(array_column($levelData, 'balance')) !!}
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Ksh ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
