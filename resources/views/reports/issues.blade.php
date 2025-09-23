@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Issue Summary</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle</th>
              <th>Summary</th>
              <th>Status</th>
              <th>Reported On</th>
            </tr>
          </thead>
          <tbody>
            @foreach($issues as $issue)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $issue->vehicle->license_plate ?? '-' }}</td>
                <td>{{ $issue->summary ?? '-' }}</td>
                <td>{{ $issue->status ?? 'Open' }}</td>
                <td>{{ $issue->created_at->format('d M Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
