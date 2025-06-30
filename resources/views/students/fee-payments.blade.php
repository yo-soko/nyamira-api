@extends('layout.mainlayout')
@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <h4 class="mb-4">Fee Payment History</h4>
        <div class="alert alert-info d-flex justify-content-between align-items-center">
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

        </div>

        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <select name="year" class="form-control">
                    <option value="">-- Filter by Year --</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('student.fee-payments') }}" class="btn btn-light">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Receipt #</th>
                        <th>Term</th>
                        <th>Payment Mode</th>
                        <th>Amount Paid</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('d M Y') }}</td>
                            <td>{{ $payment->receipt_number ?? '-' }}</td>
                            <td>{{ $payment->term->term_name ?? '-' }}</td>
                            <td>{{ $payment->payment_mode }}</td>
                            <td>Ksh {{ number_format($payment->amount_paid, 2) }}</td>
                            <td>{{ $payment->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $payments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
