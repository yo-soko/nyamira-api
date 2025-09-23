@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Work Orders by Vehicle</h3>
    <div class="card">
      <div class="card-body">
        @foreach($orders as $vehicleId => $vehicleOrders)
          <h5 class="mt-3">Vehicle: {{ $vehicleOrders->first()->vehicle->plate_number ?? '-' }}</h5>
          <table class="table table-sm table-striped mb-4">
            <thead>
              <tr>
                <th>#</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($vehicleOrders as $order)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $order->priority_class ?? '-' }}</td>
                  <td>{{ $order->status ?? 'Pending' }}</td>
                  <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
