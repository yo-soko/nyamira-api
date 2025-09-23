@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="page-title">Standard Reports</h4>
        </div>
        <div class="container">
            <h3>Driver Report</h3>
            <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($drivers as $driver)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $driver->name }}</td>
                <td>{{ $driver->phone }}</td>
                <td>{{ $driver->status }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        </div>
        </div>
        </div>
@endsection
