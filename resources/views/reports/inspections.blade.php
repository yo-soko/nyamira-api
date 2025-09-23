@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Inspection Summary</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle</th>
              <th>Inspector</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($inspections as $inspection)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inspection->vehicle->license_plate ?? '-' }}</td>
                <td>{{ $inspection->inspector->name ?? '-' }}</td>
                <td>{{ $inspection->created_at->format('d M Y') }}</td>
                <td>{{ $inspection->status ?? 'N/A' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
