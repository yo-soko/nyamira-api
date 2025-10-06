@can('view work-ticket')
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="page-title">Driver Work Tickets</h4>
      @can('add work-ticket')
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkTicketModal">
        <i class="ti ti-plus"></i> New Work Ticket
      </button>
      @endcan
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Date of Travel</th>
                <th>From</th>
                <th>To</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Authorizing Officer</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tickets as $ticket)
                <tr>
                  <td>{{ $ticket->id }}</td>
                  <td>{{ $ticket->user->name ?? '-' }}</td>
                  <td>{{ $ticket->vehicle->license_plate ?? '-' }}</td>
                  <td>{{ $ticket->travel_date?->format('M d, Y') }}</td>
                  <td>{{ $ticket->start_point }}</td>
                  <td>{{ $ticket->end_point }}</td>
                  <td>{{ Str::limit($ticket->purpose, 40) }}</td>
                  <td class="text-center">
                    <span class="badge bg-{{ 
                        $ticket->status == 'approved' ? 'success' : 
                        ($ticket->status == 'pending' ? 'warning' : 'danger') 
                    }}">
                      {{ ucfirst($ticket->status) }}
                    </span>

                    @can('edit work-ticket')
                      @if($ticket->status === 'pending')
                        <div class="mt-2 d-flex gap-1 justify-content-center">
                          <!-- Approve Button -->
                          <button 
                            class="btn btn-success btn-sm me-3" 
                            data-bs-toggle="modal" 
                            data-bs-target="#approveWorkTicketModal" 
                            data-id="{{ $ticket->id }}"
                          >
                            <i class="fa fa-check"></i> Approve
                          </button>

                          <!-- Reject Button -->
                          <button 
                            class="btn btn-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#rejectWorkTicketModal" 
                            data-id="{{ $ticket->id }}"
                          >
                            <i class="fa fa-times"></i> Reject
                          </button>
                        </div>
                      @endif
                    @endcan
                  </td>


                  <td>{{ $ticket->approver->name ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center text-muted">No work tickets found</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          {{ $tickets->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Work Ticket Modal -->
<div class="modal fade" id="addWorkTicketModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <form action="{{ route('work_tickets.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-primary text-white sticky-top">
          <h5 class="modal-title">Apply for Work Ticket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- MAKE THIS BODY SCROLLABLE -->
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <div class="row g-3">
            <!-- Driver -->
            <div class="col-lg-6">
              <label class="form-label">Driver Name</label>
              <input type="text" class="form-control" value="{{ auth()->user()->name ?? '' }}" readonly>
            </div>

            <div class="col-lg-6">
              <label class="form-label">Personal Number</label>
              <input type="text" 
                    class="form-control" 
                    value="{{ auth()->user()->driver->personal_number ?? '' }}" 
                    readonly>
            </div>


            <!-- Vehicle -->
            <div class="col-lg-6">
              <label class="form-label">Assigned Vehicle</label>
              @if($vehicles->count() === 1)
                @php $v = $vehicles->first(); @endphp
                <input type="hidden" name="vehicle_id" value="{{ $v->vehicle->id }}">
                <input type="text" class="form-control" 
                      value="{{ $v->vehicle->license_plate }} - {{ $v->vehicle->make }} {{ $v->vehicle->model }}" readonly>
              @elseif($vehicles->count() > 1)
                <select name="vehicle_id" class="form-select" required>
                  <option value="">-- Select Vehicle --</option>
                  @foreach($vehicles as $v)
                    <option value="{{ $v->vehicle->id }}">
                      {{ $v->vehicle->license_plate }} - {{ $v->vehicle->make }} {{ $v->vehicle->model }}
                    </option>
                  @endforeach
                </select>
              @else
                <input type="text" class="form-control" value="No assigned vehicle" readonly>
              @endif
            </div>

            <div class="col-lg-6">
              <label class="form-label">Department</label>
              <input type="text" 
                    class="form-control" 
                    value="{{ auth()->user()->driver->department->name ?? '' }}" 
                    readonly>
            </div>
            <div class="col-lg-4">
              <label class="form-label">Date of Travel <span class="text-danger">*</span></label>
              <input type="date" name="travel_date" class="form-control" required>
            </div>

            <div class="col-lg-4">
              <label class="form-label">Starting Point <span class="text-danger">*</span></label>
              <input type="text" name="start_point" class="form-control" required>
            </div>

            <div class="col-lg-4">
              <label class="form-label">Destination <span class="text-danger">*</span></label>
              <input type="text" name="destination" class="form-control" required>
            </div>
            <div class="col-lg-6">
              <label class="form-label">Current Milleage <span class="text-danger">*</span></label>
              <input type="text" name="start_mileage" class="form-control" required>
            </div>
             <div class="col-lg-6">
              <label class="form-label">Estimated Distance<span class="text-danger"></span></label>
              <input type="text" name="estimated_distance" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Purpose of Trip <span class="text-danger">*</span></label>
              <textarea name="purpose" class="form-control" rows="2" required></textarea>
            </div>

            <!-- Passengers -->
            <div class="col-12">
              <label class="form-label">Passengers</label>
              <div id="passenger-list">
                <div class="row passenger-row mb-2">
                  <div class="col-lg-6">
                    <input type="text" name="passenger_names[]" class="form-control" placeholder="Passenger Name">
                  </div>
                  <div class="col-lg-4">
                    <input type="text" name="passenger_numbers[]" class="form-control" placeholder="ID / Staff No.">
                  </div>
                  <div class="col-lg-2">
                    <button type="button" class="btn btn-danger w-100 remove-passenger">Remove</button>
                  </div>
                </div>
              </div>
              <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addPassenger">+ Add Passenger</button>
            </div>

            <div class="col-lg-6">
              <label class="form-label">Fuel Consumed (L)</label>
              <input type="number" step="0.01" name="fuel_consumed" class="form-control" placeholder="e.g., 15.2">
            </div>

            <div class="col-lg-6">
              <label class="form-label">Fuel Source</label>
              <select name="fuel_source" class="form-select select2">
                    <option value="">--Select here--</option>
                  @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                  @endforeach
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">Driver’s Log / Notes</label>
              <textarea name="notes" class="form-control" rows="3" placeholder="Mileage, time, or other trip details..."></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer sticky-bottom bg-light">
          <button type="submit" class="btn btn-primary">Submit Work Ticket</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="rejectWorkTicketModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="rejectWorkTicketForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Reject Work Ticket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="work_ticket_id" id="reject_work_ticket_id">

          <div class="mb-3">
            <label for="rejection_remarks" class="form-label">Rejection Remarks</label>
            <textarea name="rejection_remarks" id="rejection_remarks" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label for="signature_reject" class="form-label">Signature (Authorizing Officer)</label>
            <input type="text" name="authorized_signature" id="signature_reject" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Reject Ticket</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="approveWorkTicketModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <form id="approveWorkTicketForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Approve Work Ticket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="work_ticket_id" id="approve_work_ticket_id">

          <div class="mb-3">
            <label for="approval_remarks" class="form-label">Approval Remarks</label>
            <textarea name="approval_remarks" id="approval_remarks" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label for="signature" class="form-label">Signature (Authorizing Officer)</label>
            <input type="text" name="authorized_signature" id="signature" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Approve Ticket</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS for dynamic passenger list -->
<script>
document.getElementById('addPassenger').addEventListener('click', function() {
  const row = document.createElement('div');
  row.className = 'row passenger-row mb-2';
  row.innerHTML = `
    <div class="col-lg-6"><input type="text" name="passenger_names[]" class="form-control" placeholder="Passenger Name"></div>
    <div class="col-lg-4"><input type="text" name="passenger_numbers[]" class="form-control" placeholder="ID / Staff No."></div>
    <div class="col-lg-2"><button type="button" class="btn btn-danger w-100 remove-passenger">Remove</button></div>
  `;
  document.getElementById('passenger-list').appendChild(row);
});

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-passenger')) {
    e.target.closest('.passenger-row').remove();
  }
});
document.addEventListener('DOMContentLoaded', function() {
    // Approve Modal
    const approveModal = document.getElementById('approveWorkTicketModal');
    approveModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        document.getElementById('approve_work_ticket_id').value = id;
        document.getElementById('approveWorkTicketForm').action = `/work_tickets/${id}/approve`;
    });

    // Reject Modal
    const rejectModal = document.getElementById('rejectWorkTicketModal');
    rejectModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        document.getElementById('reject_work_ticket_id').value = id;
        document.getElementById('rejectWorkTicketForm').action = `/work_tickets/${id}/reject`;
    });
});


</script>

@endsection
@endcan