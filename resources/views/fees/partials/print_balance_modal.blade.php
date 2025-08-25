<div class="modal fade" id="printBalanceModal" tabindex="-1" aria-labelledby="printBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="GET" action="{{ route('print.fee.balances') }}" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title" id="printBalanceModalLabel">Print Fee Balances</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Class Level & Stream Selection --}}
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Select Class / Stream</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">All Students</option>
                            @foreach($classLevels as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->level?->level_name }} {{ $class->stream?->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Leave as "All Students" to print balances for everyone.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-printer me-1"></i> Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
