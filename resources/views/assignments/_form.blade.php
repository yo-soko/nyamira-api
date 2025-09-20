<form id="assignmentForm" action="{{ route('assignments.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="vehicle_id">Assigned Vehicle</label>
            <select name="vehicle_id" id="vehicle_id" class="form-select select2" required>
                <option value="">-- Select Vehicle --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_name }} ({{ $vehicle->license_plate }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="operator_id">Operator</label>
            <select name="operator_id" id="operator_id" class="form-select select2" required>
                <option value="">-- Select Operator --</option>
                @foreach($operators as $operator)
                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date/Time</label>
            <input type="datetime-local" name="start_at" id="start_at" class="form-control">
        </div>

        <div class="form-group">
            <label for="end_date">End Date/Time</label>
            <input type="datetime-local" name="end_at" id="end_at" class="form-control">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Assignment</button>
    </div>
</form>
