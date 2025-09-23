@extends('layout.mainlayout')

@section('content')
@include('layout.toast')
<div class="container">
    <h2>Create Inspection</h2>

    <form method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Vehicle -->
        <div class="mb-3">
            <label class="form-label">Vehicle *</label>
            <select name="vehicle_id" class="form-control" required>
                <option value="">-- Select Vehicle --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->name ?? $vehicle->model }}</option>
                @endforeach
            </select>
        </div>

        <!-- Odometer -->
        <div class="mb-3">
            <label class="form-label">Odometer Reading</label>
            <input type="number" name="odometer_reading" class="form-control">
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <hr>
        <h5>Checklist</h5>

        @foreach($checklist as $item)
            <div class="card mb-3 p-3">
                <strong>{{ $item }}</strong>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <select name="items[{{ $item }}][status]" class="form-control" required>
                            <option value="Pass">Pass</option>
                            <option value="Fail">Fail</option>
                            <option value="N/A">N/A</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="items[{{ $item }}][remark]" class="form-control" placeholder="Remark">
                    </div>
                    <div class="col-md-5">
                        <input type="file" name="items[{{ $item }}][attachment]" class="form-control">
                    </div>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Save Inspection</button>
    </form>
</div>
@endsection
