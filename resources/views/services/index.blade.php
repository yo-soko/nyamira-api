@can('view service')

@extends('layout.mainlayout')

@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="page-title">Service Entries</h4>
@can('add service')

      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        <i class="ti ti-plus"></i> Add Service Entry
      </button>
      @endcan
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Vehicle</th>
                <th>Priority</th>
                <th>Odometer</th>
                <th>Completion Date</th>
                <th>Vendor</th>
                <th>Issues</th>
                <th>Total Cost (KES)</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($services as $service)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    {{ $service->vehicle->fleet_no ?? '' }}
                    [{{ $service->vehicle->make ?? '' }} {{ $service->vehicle->model ?? '' }}]
                  </td>
                  <td>
                    <span class="badge bg-{{ $service->priority_class == 'Emergency' ? 'danger' : 'secondary' }}">
                      {{ $service->priority_class }}
                    </span>
                  </td>
                  <td>{{ number_format($service->odometer) }} mi</td>
                  <td>{{ $service->completion_date ? $service->completion_date->format('M d, Y h:i A') : '-' }}</td>
                  <td>{{ $service->vendor_id ?? '-' }}</td>
                  <td>
                    @if($service->issues->isEmpty())
                        <span class="text-muted">No Issues</span>
                    @else
                        @foreach($service->issues as $issue)
                        <span class="badge bg-info">#{{ $issue->id }} {{ $issue->summary }}</span>
                        @endforeach
                    @endif
                  </td>
                  <td>{{ number_format($service->total_cost, 2) }}</td>
                  <td>
                    @if($service->status == 'completed')
                      <span class="badge bg-success">Completed</span>
                    @elseif($service->status == 'in_progress')
                      <span class="badge bg-warning">In Progress</span>
                    @else
                      <span class="badge bg-secondary">Pending</span>
                    @endif
                  </td>
                  <td>
                    <a href="#" class="btn btn-sm btn-info">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-warning">
                      <i class="ti ti-edit"></i>
                    </a>
                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this service entry?')">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center">No service entries found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $services->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal for Create --}}
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addServiceModalLabel">Add Service Entry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

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
            <label>Service Stations</label>       
            <input type="text" name="vendor_id" class="form-control">
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

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Labor Cost</label>
              <input type="number" step="0.01" name="labor_cost" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label>Parts Cost</label>
              <input type="number" step="0.01" name="parts_cost" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Discount</label>
              <input type="number" step="0.01" name="discount" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label>Tax</label>
              <input type="number" step="0.01" name="tax" class="form-control">
            </div>
          </div>

          <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Service Entry</button>
        </div>
      </form>
    </div>
  </div>
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
@endcan
