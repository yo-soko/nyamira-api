@extends('layout.mainlayout')


@section('content')
@include('layout.toast')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="page-title">Standard Reports</h4>
        </div>
        <div class="container">
            <h3>Work Orders List</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->vehicle->license_plate ?? 'N/A' }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
        </div>
@endsection
