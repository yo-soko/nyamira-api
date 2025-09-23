@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="page-title">Work Orders Section</h4>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkModal">
        <i class="ti ti-plus"></i> New Work Order
      </button>
    </div>
  
    <div class="card">    
      <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Issue Date</th>
                        <th>Issued By</th>
                        <th>Assigned To</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->vehicle->make ?? '' }} {{ $order->vehicle->model ?? '' }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->priority_class }}</td>
                            <td>{{ $order->issue_date?->format('M d, Y h:i A') }}</td>
                            <td>{{ $order->issuedBy->name ?? '-' }}</td>
                            <td>{{ $order->assignedTo->name ?? '-' }}</td>
                            <td>{{ number_format($order->total_cost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    {{ $workOrders->links() }}
    </div>
    </div>
    </div>
</div>
</div>
<!-- Trigger Button -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkModal">
  <i class="ti ti-plus"></i> New Work Order
</button>

<!-- Modal -->
<div class="modal fade" id="addWorkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form action="{{ route('work_orders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Create Work Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Nav Tabs -->
          <ul class="nav nav-tabs" id="workOrderTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#details">Details</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#scheduling">Scheduling</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#costs">Costs</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#attachments">Attachments</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#comments">Comments</a></li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content mt-3">

            <!-- Details Tab -->
            <div class="tab-pane fade show active" id="details">
              <div class="row g-3">
                <div class="col-md-6">
                  <label>Vehicle *</label>
                  <select name="vehicle_id" class="form-control" required>
                    @foreach($vehicles as $vehicle)
                      <option value="{{ $vehicle->id }}">
                        {{ $vehicle->id }} [{{ $vehicle->make }} {{ $vehicle->model }}]
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6">
                  <label>Status *</label>
                  <select name="status" class="form-control">
                    <option>Pending</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                    <option>Voided</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label>Repair Priority Class *</label>
                  <select name="priority_class" class="form-control">
                    <option>Scheduled</option>
                    <option>Non-Scheduled</option>
                    <option>Emergency</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label>Issue Date *</label>
                  <input type="datetime-local" name="issue_date" class="form-control" required>
                </div>

                <div class="col-md-6">
                  <label>Issued By *</label>
                  <select name="issued_by" class="form-control">
                    @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6">
                  <label>Assigned To</label>
                  <select name="assigned_to" class="form-control">
                    <option value="">-- None --</option>
                    @foreach($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-12">
                  <label>Issues</label>
                  <select name="issues[]" class="form-control" multiple>
                    @foreach($issues as $issue)
                      <option value="{{ $issue->id }}">#{{ $issue->id }} {{ $issue->summary }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <!-- Scheduling Tab -->
            <div class="tab-pane fade" id="scheduling">
              <div class="row g-3">
                <div class="col-md-6">
                  <label>Scheduled Start Date</label>
                  <input type="datetime-local" name="scheduled_start_date" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Actual Start Date</label>
                  <input type="datetime-local" name="actual_start_date" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Expected Completion Date</label>
                  <input type="datetime-local" name="expected_completion_date" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Actual Completion Date</label>
                  <input type="datetime-local" name="actual_completion_date" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Start Odometer (mi)</label>
                  <input type="number" name="start_odometer" class="form-control">
                </div>
                <div class="col-md-6 form-check mt-4">
                  <input type="checkbox" name="send_start_reminder" class="form-check-input">
                  <label class="form-check-label">Send Scheduled Start Reminder</label>
                </div>
                <div class="col-md-6 form-check mt-4">
                  <input type="checkbox" name="use_start_odometer" class="form-check-input">
                  <label class="form-check-label">Use Start Odometer for Completion Meter</label>
                </div>
              </div>
            </div>

            <!-- Costs Tab -->
            <div class="tab-pane fade" id="costs">
              <div class="row g-3">
                <div class="col-md-6">
                  <label>Labor Cost</label>
                  <input type="number" name="labor_cost" step="0.01" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Parts Cost</label>
                  <input type="number" name="parts_cost" step="0.01" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Discount (%)</label>
                  <input type="number" name="discount" step="0.01" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Tax (%)</label>
                  <input type="number" name="tax" step="0.01" class="form-control">
                </div>
                <div class="col-12">
                  <label>Total (auto-calculated)</label>
                  <input type="text" name="total_cost" class="form-control" readonly>
                </div>
              </div>
            </div>

            <!-- Attachments Tab -->
            <div class="tab-pane fade" id="attachments">
              <div class="mb-3">
                <label>Photos</label>
                <input type="file" name="photos[]" class="form-control" multiple>
              </div>
              <div class="mb-3">
                <label>Documents</label>
                <input type="file" name="documents[]" class="form-control" multiple>
              </div>
            </div>

            <!-- Comments Tab -->
            <div class="tab-pane fade" id="comments">
              <div class="mb-3">
                <label>Comments</label>
                <textarea name="comments" class="form-control" rows="4"></textarea>
              </div>
            </div>

          </div> <!-- /tab-content -->
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Work Order</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
