@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Vehicle Assignments</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle</th>
              <th>Driver</th>
              <th>Assigned At</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($assignments as $assignment)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $assignment->vehicle->license_plate ?? '-' }}</td>
                <td>{{ $assignment->operator->name ?? '-' }}</td>
                <td>{{ $assignment->created_at->format('d M Y') }}</td>
                <td>{{ $assignment->status ?? 'Active' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
