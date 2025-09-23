@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Fuel History</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle</th>
              <th>Fuel Type</th>
              <th>Liters</th>
              <th>Cost</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($fuels as $fuel)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $fuel->vehicle->license_plate ?? '-' }}</td>
                <td>{{ $fuel->fuel_type ?? '-' }}</td>
                <td>{{ $fuel->liters }}</td>
                <td>{{ number_format($fuel->cost, 2) }}</td>
                <td>{{ $fuel->created_at->format('d M Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
