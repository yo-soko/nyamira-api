<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Stream</th>
                        <th>Term</th>
                        <th>Amount Paid</th>
                        <th>Receipt Number</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->student->full_name ?? 'N/A' }}</td>
                        <td>
                            {{ $payment->classLevel->level->level_name ?? 'No Level' }}
                            -
                            {{ $payment->classLevel->stream->name ?? 'No Stream' }}
                        </td>

                        <td>{{ $payment->term->term_name ?? 'N/A' }}</td>
                        <td>KSh {{ number_format($payment->amount_paid, 2) }}</td>
                        <td>{{ $payment->receipt_number }}</td>
                        <td>{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</td>
                        <td>
                            <button class="btn btn-sm btn-info editPaymentBtn" data-id="{{ $payment->id }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger deletePaymentBtn" data-id="{{ $payment->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
