@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <h2>Driver History: {{ $driver->name }}</h2>

    <h4 class="mt-4">Assignments</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Vehicle</th>
          <th>Type</th>
          <th>Location</th>
          <th>Start</th>
          <th>End</th>
        </tr>
      </thead>
      <tbody>
        @foreach($assignments as $a)
        <tr>
          <td>{{ $a->vehicle->vehicle_name ?? '' }}</td>
          <td>{{ $a->assignment_type }}</td>
          <td>{{ $a->assignment_location }}</td>
          <td>{{ $a->start_at }}</td>
          <td>{{ $a->end_at }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <h4 class="mt-4">Meter Readings</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Vehicle</th>
          <th>Meter Reading</th>
          <th>Source</th>
          <th>Recorded At</th>
        </tr>
      </thead>
      <tbody>
        @foreach($meters as $m)
        <tr>
          <td>{{ $m->vehicle->vehicle_name ?? '' }}</td>
          <td>{{ $m->meter_reading }}</td>
          <td>{{ ucfirst($m->source) }}</td>
          <td>{{ $m->recorded_at }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
