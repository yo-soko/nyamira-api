@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
  <div class="content container-fluid">
    <h3 class="page-title">Inspection Failures</h3>
    <div class="card">
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Vehicle</th>
              <th>Inspector</th>
              <th>Date</th>
              <th>Failed Items</th>
            </tr>
          </thead>
          <tbody>
            @foreach($inspections as $inspection)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inspection->vehicle->license_plate?? '-' }}</td>
                <td>{{ $inspection->inspector->name ?? '-' }}</td>
                <td>{{ $inspection->created_at->format('d M Y') }}</td>
                <td>
                  <ul>
                    @foreach($inspection->items->where('status', 'Fail') as $item)
                      <li>{{ $item->name }}</li>
                    @endforeach
                  </ul>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
