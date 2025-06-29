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
                            {{ $payment->SchoolClass->level->level_name ?? 'No Level' }}
                            -
                            {{ $payment->SchoolClass->stream->name ?? 'No Stream' }}
                        </td>

                        <td>{{ $payment->term->term_name ?? 'N/A' }}</td>
                        <td>KSh {{ number_format($payment->amount_paid, 2) }}</td>
                        <td>{{ $payment->receipt_number }}</td>
                        <td>{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</td>
                        <td class="text-end">
                            {{-- Edit Button --}}
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPaymentModal{{ $payment->id }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            {{-- Delete Button --}}
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletePaymentModal{{ $payment->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>

                    </tr>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deletePaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="deletePaymentModalLabel{{ $payment->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('fee-payments.destroy', ['fee_payment' => $payment->payment_id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this payment for student <strong>{{ $payment->student->first_name ?? 'N/A' }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <form action="{{ route('fee-payments.update', ['fee_payment' => $payment->payment_id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Stream -->
                                        <div class="mb-3">
                                            <label class="form-label">Stream</label>
                                            <select name="class_id" class="form-select" required>
                                                <option value="">-- Select Stream --</option>
                                                @foreach($classLevels as $class)
                                                    <option value="{{ $class->id }}" {{ $payment->class_id == $class->id ? 'selected' : '' }}>
                                                        {{ $class->level->level_name ?? 'No Level' }} - {{ $class->stream->name ?? 'No Stream' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Term -->
                                        <div class="mb-3">
                                            <label class="form-label">Term</label>
                                            <select name="term_id" class="form-select" required>
                                                <option value="">-- Select Term --</option>
                                                @foreach($terms as $term)
                                                    <option value="{{ $term->id }}" {{ $payment->term_id == $term->id ? 'selected' : '' }}>
                                                        {{ $term->term_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Student -->
                                        <div class="mb-3">
                                            <label class="form-label">Student</label>
                                            <select name="student_id" class="form-select" required>
                                                @foreach($students as $student)
                                                    <option value="{{ $student->id }}" {{ $payment->student_id == $student->id ? 'selected' : '' }}>
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Payment Mode -->
                                        <div class="mb-3">
                                            <label class="form-label">Payment Mode</label>
                                            <select name="payment_mode" class="form-select" required>
                                                <option value="Cash" {{ $payment->payment_mode == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="Mpesa" {{ $payment->payment_mode == 'Mpesa' ? 'selected' : '' }}>Mpesa</option>
                                                <option value="Bank" {{ $payment->payment_mode == 'Bank' ? 'selected' : '' }}>Bank</option>
                                            </select>
                                        </div>

                                        <!-- Receipt Number -->
                                        <div class="mb-3">
                                            <label class="form-label">Receipt Number</label>
                                            <input type="text" name="receipt_number" class="form-control" value="{{ $payment->receipt_number }}">
                                        </div>

                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control">{{ $payment->description }}</textarea>
                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3">
                                            <label class="form-label">Amount Paid (KSh)</label>
                                            <input type="number" name="amount_paid" step="0.01" class="form-control" value="{{ $payment->amount_paid }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update Payment</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
