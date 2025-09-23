@extends('layout.mainlayout')

@section('content')
@include('layout.toast')

<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Inspection History</h4>
                    <h6>Manage your Vehicle inspections history</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{URL::asset('build/img/icons/pdf.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{URL::asset('build/img/icons/excel.svg')}}"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inspectionModal">
                    <i class="ti ti-plus"></i> New Inspection
                </button>
            </div>

        </div>
        <!-- /Page Header -->

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Inspector</th>
                            <th>Odometer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Void</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($inspections as $inspection)
                        <tr>
                            <td>{{ $inspection->id }}</td>
                            <td>{{ $inspection->vehicle->name ?? $inspection->vehicle->model }}</td>
                            <td>{{ $inspection->inspector->name }}</td>
                            <td>{{ $inspection->odometer_reading ?? '-' }}</td>
                            <td>{{ $inspection->inspection_date->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($inspection->status === 'Pass')
                                    <span class="badge bg-success">Pass</span>
                                @elseif($inspection->status === 'Fail')
                                    <span class="badge bg-danger">Fail</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($inspection->is_void)
                                    <span class="badge bg-dark">Voided</span>
                                @else
                                    <span class="badge bg-light text-dark">Active</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-sm btn-info">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-sm btn-warning">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form action="{{ route('inspections.destroy', $inspection->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this inspection?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No inspections found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $inspections->links() }}
    </div>
</div>
<!-- Inspection Create Modal -->
<!-- Inspection Create Modal -->
<div class="modal fade" id="inspectionModal" tabindex="-1" aria-labelledby="inspectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="inspectionModalLabel">New Inspection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- scrollable body -->
        <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
          
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
          <h6 class="fw-bold">Checklist</h6>

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
          <hr>
            <h6 class="fw-bold">Sign-Off</h6>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="vehicle_condition_ok" id="vehicle_condition_ok" value="1">
                <label class="form-check-label" for="vehicle_condition_ok">
                    Vehicle Condition OK <span class="text-danger">*</span> 
                    <small class="text-muted">(Must be checked if there are no defects)</small>
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Condition Remark</label>
                <textarea name="condition_remark" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Reviewing Driver's Signature <span class="text-danger">*</span></label>
                <input type="text" name="reviewing_driver_signature" class="form-control" placeholder="Type your name to sign" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Driver Remark</label>
                <textarea name="driver_remark" class="form-control"></textarea>
            </div>


        </div>

        <!-- fixed footer always visible -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Inspection</button>
        </div>

      </form>
    </div>
  </div>
</div>



@endsection
