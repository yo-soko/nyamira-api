<div class="modal fade" id="printBalanceModal" tabindex="-1" aria-labelledby="printBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="GET" action="{{ route('print.fee.balances') }}" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title" id="printBalanceModalLabel">Print Fee Balances</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- Option A: Class / Stream --}}
                    <div class="mb-3">
                        <label for="print_class_id" class="form-label">Select Class / Stream (optional)</label>
                        <select name="class_id" id="print_class_id" class="form-select">
                            <option value="">All Students</option>
                            @foreach($classLevels as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->level?->level_name }} {{ $class->stream?->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Leave as "All Students" to print balances for everyone.</small>
                    </div>

                    {{-- Option B: Individual Student (searchable) --}}
                    <div class="mb-3">
                        <label for="print_student_search" class="form-label">Search Student (optional)</label>
                        <input type="text" id="print_student_search" class="form-control" list="studentOptions"
                            placeholder="Type name or ADM number…">
                        <datalist id="studentOptions">
                            @foreach($students as $s)
                                <option
                                    value="{{ $s->full_name }} ({{ $s->student_reg_number }}) - {{ $s->schoolClass?->level?->level_name ?? '' }}, {{ $s->schoolClass?->stream?->name ?? 'No Stream' }} - Bal: {{ number_format($s->current_balance, 2) }}"
                                    data-id="{{ $s->id }}">
                                </option>
                            @endforeach
                        </datalist>
                        {{-- actual value submitted --}}
                        <input type="hidden" name="student_id" id="print_student_id">
                        <small class="text-muted d-block mt-1">
                            Selecting a student will ignore the class filter.
                        </small>
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

{{-- Tiny JS to bind datalist selection to hidden student_id and keep filters exclusive --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('print_student_search');
    const hiddenId    = document.getElementById('print_student_id');
    const dataList    = document.getElementById('studentOptions');
    const classSelect = document.getElementById('print_class_id');

    function syncStudentId() {
        const val = searchInput.value;
        let matchedId = '';
        for (const opt of dataList.options) {
            if (opt.value === val) {
                matchedId = opt.dataset.id || '';
                break;
            }
        }
        hiddenId.value = matchedId;

        // If a student is chosen, clear the class filter to avoid ambiguity
        if (matchedId) classSelect.value = '';
    }

    searchInput.addEventListener('input', syncStudentId);

    // If class is picked later, clear the student selection
    classSelect.addEventListener('change', () => {
        if (classSelect.value) {
            searchInput.value = '';
            hiddenId.value = '';
        }
    });
});
</script>
