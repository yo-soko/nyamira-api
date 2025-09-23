@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
  <div class="content">
    <h3>Select a Driver</h3>
    <ul class="list-group">
      @foreach($drivers as $driver)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>{{ $loop->iteration }}. {{ $driver->name }}</span>
          <a href="{{ route('driver-history.show', $driver->id) }}" class="btn btn-primary btn-sm">View History</a>
        </li>
      @endforeach
    </ul>
  </div>
</div>

@endsection
