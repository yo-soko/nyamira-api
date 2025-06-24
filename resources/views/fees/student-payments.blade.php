@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-title">
            <h4>{{ $student->first_name }} {{ $student->last_name }} - Payment History</h4>
        </div>

        <div class="card">
            <div class="card-header"><strong>All Payments</strong></div>
            <div class="card-body">
                @if($payments->isEmpty())
                    <p class="text-muted">No payments made yet.</p>
                @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Term</th>
                            <th>Mode</th>
                            <th>Amount (KSh)</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <td>
                                    {{ $payment->schoolClass->level->level_name ?? 'N/A' }} -
                                    {{ $payment->schoolClass->stream->name ?? 'N/A' }}
                                </td>
                                <td>{{ $payment->term->term_name ?? 'N/A' }}</td>
                                <td>{{ $payment->payment_mode }}</td>
                                <td>{{ number_format($payment->amount_paid, 2) }}</td>
                                <td>{{ $payment->description ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <a href="{{ route('fee-payments') }}" class="btn btn-secondary mt-3">← Back to Payments</a>
    </div>
</div>
@endsection
