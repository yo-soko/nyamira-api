@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Vehicle Report</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Plate Number</th>
              <th>Model</th>
              <th>Type</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vehicles as $vehicle)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $vehicle->license_plate }}</td>
                <td>{{ $vehicle->model }}</td>
                <td>{{ $vehicle->type }}</td>
                <td>{{ $vehicle->status }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
