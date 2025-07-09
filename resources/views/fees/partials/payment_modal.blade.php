<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form id="addPaymentForm" method="POST" action="{{ route('fee-payments.store') }}">

            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Fee Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Class Level -->
                        <div class="col-md-6 mb-3">
                            <label for="class_id" class="form-label">Stream</label>
                            <select name="class_id" id="class_id" class="form-select" required>
                                <option value="">-- Select Stream --</option>
                                @foreach($classLevels as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->level?->level_name ?? 'No Level' }} - {{ $class->stream?->name ?? 'No Stream' }}
                                </option>

                                @endforeach


                            </select>

                        </div>

                        <!-- Term -->
                        <div class="col-md-6 mb-3">
                            <label for="term_id" class="form-label">Term</label>
                            <select name="term_id" id="term_id" class="form-select" required>
                                <option value="">-- Select Term --</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->term_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Student -->
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="">-- Select Student --</option>
                                {{-- Options populated dynamically via JS --}}
                            </select>
                        </div>

                        <!-- Outstanding Balance -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Outstanding Balance</label>
                            <input type="text" id="outstanding_balance" class="form-control" disabled value="0.00">
                        </div>

                        <!-- Payment Mode -->
                        <div class="col-md-6 mb-3">
                            <label for="payment_mode" class="form-label">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-select" required>
                                <option value="">-- Select Payment Mode --</option>
                                <option value="Cash">Cash</option>
                                <option value="Mpesa">Mpesa</option>
                                <option value="Bank">Bank</option>
                            </select>
                        </div>

                        <!-- Receipt Number -->
                        <div class="col-md-6 mb-3">
                            <label for="receipt_number" class="form-label">Receipt Number</label>
                            <input type="text" name="receipt_number" id="receipt_number" class="form-control" placeholder="Enter receipt number" required>
                        </div>

                        <!-- Description -->
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Payment For</label>
                            <select name="description" id="description" class="form-select" required>
                                <option value="">-- Select Payment Purpose --</option>
                                {{-- Options populated via JS --}}
                            </select>
                        </div>


                        <!-- Amount Paid -->
                        <div class="col-md-6 mb-3">
                            <label for="amount_paid" class="form-label">Amount Paid (KSh)</label>
                            <input type="number" name="amount_paid" id="amount_paid" class="form-control" step="0.01" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="savePaymentBtn" class="btn btn-primary">Save Payment</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
