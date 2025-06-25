@extends('layouts.mainlayout')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>Fee Payments</h4>
        <h6>Manage student payments</h6>
    </div>
    <div class="page-btn">
        @role('admin|superAdmin')
        <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i class="fa fa-plus"></i> Add Payment
        </a>
        @endrole
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>Receipt No.</th>
                        <th>Amount Paid</th>
                        <th>Date</th>
                        @role('admin|superAdmin')
                        <th>Actions</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->student->full_name }}</td> {{-- ✅ Fixed --}}
                        <td>{{ $payment->class->level_name }}</td> 
                        <td>{{ $payment->term->term_name }}</td>
                        <td>{{ $payment->receipt_number }}</td>
                        <td>{{ number_format($payment->amount_paid, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}</td>
                        @role('admin|superAdmin')
                        <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning editPaymentBtn" data-id="{{ $payment->id }}">Edit</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger deletePaymentBtn" data-id="{{ $payment->id }}">Delete</a>
                        </td>
                        @endrole
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="paymentForm">
      @csrf
      <input type="hidden" id="payment_id" name="id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add/Edit Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label>Class Level</label>
            <select name="class_id" class="form-control" required>
              <option value="">Select Class</option>
              @foreach($classLevels as $class)
              <option value="{{ $class->id }}">{{ $class->level_name }}</option> {{-- ✅ Fixed --}}
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label>Term</label>
            <select name="term_id" class="form-control" required>
              <option value="">Select Term</option>
              @foreach($terms as $term)
              <option value="{{ $term->id }}">{{ $term->term_name }}</option> {{-- ✅ Fixed --}}
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label>Student</label>
            <select name="student_id" class="form-control" required>
              <option value="">Select Student</option>
              @foreach($students as $student)
              <option value="{{ $student->id }}">{{ $student->full_name }}</option> {{-- ✅ Fixed --}}
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label>Receipt Number</label>
            <input type="text" name="receipt_number" class="form-control" placeholder="Optional">
          </div>
          <div class="col-md-6">
            <label>Amount Paid</label>
            <input type="number" step="0.01" name="amount_paid" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer mt-3">
          <button type="submit" class="btn btn-primary">Save Payment</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/payments.js') }}"></script>
@endsection
