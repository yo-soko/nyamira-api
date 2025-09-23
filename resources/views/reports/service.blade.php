@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Service Entries</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle</th>
              <th>Priority</th>
              <th>Cost</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($services as $service)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $service->vehicle->license_plate?? '-' }}</td>
                <td>{{ $service->priority_class ?? '-' }}</td>
                <td>{{ number_format($service->cost, 2) }}</td>
                <td>{{ $service->created_at->format('d M Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
