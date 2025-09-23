@extends('layout.mainlayout')

@section('content')
<div class="container">
    <h2>Add Service Entry</h2>
    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Vehicle *</label>
            <select id="vehicle_id" name="vehicle_id" class="form-select" required>
                <option value="">-- Select Vehicle --</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->vehicle_name }} ({{ $v->license_plate }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Repair Priority Class</label>
            <select name="priority_class" class="form-select">
                <option value="Scheduled">Scheduled</option>
                <option value="Non-Scheduled">Non-Scheduled</option>
                <option value="Emergency">Emergency</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Odometer</label>
            <input type="number" name="odometer" class="form-control">
        </div>

        <div class="mb-3">
            <label>Completion Date *</label>
            <input type="datetime-local" name="completion_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Start Date</label>
            <input type="datetime-local" name="start_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Reference</label>
            <input type="text" name="reference" class="form-control">
        </div>

        <div class="mb-3">
            <label>Vendor</label>
            <select name="vendor_id" class="form-select">
                <option value="">-- Select Vendor --</option>
                @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Labels</label>
            <input type="text" name="labels" class="form-control" placeholder="e.g. Oil Change, Brake Service">
        </div>

        <div class="mb-3">
            <label>Issues Resolved</label>
            <div id="issuesContainer">
                <p class="text-muted">Select a vehicle to load open issues...</p>
            </div>
        </div>

        <div class="mb-3">
            <label>Labor Cost</label>
            <input type="number" step="0.01" name="labor_cost" class="form-control">
        </div>

        <div class="mb-3">
            <label>Parts Cost</label>
            <input type="number" step="0.01" name="parts_cost" class="form-control">
        </div>

        <div class="mb-3">
            <label>Discount</label>
            <input type="number" step="0.01" name="discount" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tax</label>
            <input type="number" step="0.01" name="tax" class="form-control">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Service Entry</button>
    </form>
</div>

<script>
document.getElementById('vehicle_id').addEventListener('change', function() {
    let vehicleId = this.value;
    let issuesContainer = document.getElementById('issuesContainer');

    if (!vehicleId) {
        issuesContainer.innerHTML = "<p class='text-muted'>Select a vehicle to load open issues...</p>";
        return;
    }

    fetch(`/services/issues/${vehicleId}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                issuesContainer.innerHTML = "<p class='text-muted'>No open issues for this vehicle.</p>";
            } else {
                let html = '';
                data.forEach(issue => {
                    html += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="issues[]" value="${issue.id}" id="issue_${issue.id}">
                            <label class="form-check-label" for="issue_${issue.id}">
                                [#${issue.id}] ${issue.summary} - ${issue.status}
                            </label>
                        </div>
                    `;
                });
                issuesContainer.innerHTML = html;
            }
        });
});
</script>
@endsection
